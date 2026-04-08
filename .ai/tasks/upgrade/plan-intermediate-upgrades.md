# Plan: Obsługa pośrednich upgrade'ów (punkt 2)

## Problem

Użytkownik chce przejść z 4.2.0 → 4.3.1, ale tag 4.3.1 **nie ma** katalogu `upgrade/4.3.1/`.
Istnieje natomiast `upgrade/4.3.0/` (dostępny na tagu 4.3.1).

System musi:
1. Wykryć, że między 4.2.0 a 4.3.1 istnieje pośredni upgrade (4.3.0).
2. Zrobić `git checkout 4.3.1`, ale uruchomić skrypty z `upgrade/4.3.0/`.
3. Jeśli pośrednich upgradów jest **≥ 2** → error z instrukcją krok po kroku.

---

## Algorytm: resolveEffectiveUpgradeVersion

```
Wejście: $tag (docelowy), $current_version

1. Czy upgrade/{tag}/ istnieje na tagu $tag?
   → TAK: zwróć $tag (normalny flow, bez zmian)

2. Pobierz listę wszystkich katalogów wewnątrz upgrade/ na tagu $tag
   (git ls-tree --name-only {tag}:upgrade/ 2>/dev/null)

3. Filtruj: zostaw tylko wersje, dla których:
   - version > $current_version
   - version < $tag
   (czyli pośrednie upgrade'y)

4. Posortuj rosnąco (version:refname)

5. count == 0 → zwróć $tag
   (brak skryptów — istniejący kod już to obsługuje gracefully)

6. count == 1 → zwróć $intermediate[0]
   (checkout do $tag, skrypty z $intermediate[0])

7. count >= 2 → zwróć null + ustaw błąd
   (nie można przeskoczyć, pokaż ścieżkę krok po kroku)
```

---

## Zmiany do wprowadzenia

### 1. `UpgradeService.php` — nowa metoda

```php
/**
 * List upgrade subdirectory names present in upgrade/ on the given tag.
 * Uses git ls-tree — no checkout needed.
 * Returns e.g. ['4.3.0', '4.3.1']
 */
public function getUpgradeVersionsOnTag(string $tag): array
{
    $raw = shell_exec("git ls-tree --name-only " . escapeshellarg("{$tag}:upgrade/") . " 2>/dev/null");
    if (empty($raw)) {
        return [];
    }
    $entries = array_filter(array_map('trim', explode("\n", $raw)));
    // Keep only semver-looking entries
    return array_values(array_filter($entries, fn($e) => preg_match('/^\d+\.\d+/', $e)));
}
```

### 2. `Upgrade.php` — nowa prywatna metoda + zmiana execute()

#### Nowa metoda `resolveEffectiveUpgradeVersion`

```php
/**
 * Determine which upgrade/{version}/ scripts to use.
 *
 * Returns the version string to pass to runPreUpgrade/runMigrations/runPostUpgrade.
 * Returns null when there are ≥2 intermediate upgrades (caller must abort with instructions).
 *
 * Sets $this->intermediate_upgrade_error on failure.
 */
private function resolveEffectiveUpgradeVersion(string $tag, string $current_version): ?string
{
    // Case 1: direct upgrade directory exists on the target tag
    $versions_on_tag = $this->upgrade_service->getUpgradeVersionsOnTag($tag);
    if (in_array($tag, $versions_on_tag, true)) {
        return $tag;
    }

    // Find intermediates: versions between current and target that have upgrade dirs
    $intermediates = array_values(array_filter(
        $versions_on_tag,
        fn($v) => version_compare($v, $current_version, '>') && version_compare($v, $tag, '<')
    ));
    usort($intermediates, 'version_compare');

    if (count($intermediates) === 0) {
        // No upgrade scripts — existing code handles missing dirs gracefully
        return $tag;
    }

    if (count($intermediates) === 1) {
        return $intermediates[0];
    }

    // ≥2 intermediates — cannot skip
    $this->intermediate_upgrade_error = $intermediates;
    return null;
}
```

#### Zmiana w `execute()` — po `resolveTag()`, przed `checkRequirements()`

```php
$effective_upgrade = $this->resolveEffectiveUpgradeVersion($tag, $current_version);

if ($effective_upgrade === null) {
    $steps = implode(' → ', array_merge([$current_version], $this->intermediate_upgrade_error, [$tag]));
    $this->io->error([
        "Cannot upgrade directly from {$current_version} to {$tag}.",
        "There are multiple intermediate upgrades required.",
        "Please upgrade step by step: {$steps}",
    ]);
    return Command::FAILURE;
}

if ($effective_upgrade !== $tag) {
    $this->io->note("No direct upgrade/{$tag}/ found. Will use upgrade scripts from: {$effective_upgrade}");
}
```

#### Zmiana kroków — przekazanie `$effective_upgrade` zamiast `$tag`

W tablicy `$steps` (execute):
```php
'pre_upgrade'  => fn() => $this->runPreUpgrade($effective_upgrade),
'migrations'   => fn() => $this->runMigrations($effective_upgrade),
'post_upgrade' => fn() => $this->runPostUpgrade($effective_upgrade),
```

> `fetch_and_checkout`, `apply_permissions`, `instance_rebuild` pozostają z `$tag`.

#### Requirements — czytamy z `$effective_upgrade` (lub `$tag` jako fallback)

W `checkRequirements()`:
```php
$requirements = $this->requirements_service->loadRequirementsFromTag($tag);
if ($requirements === null && $effective_upgrade !== $tag) {
    // Try reading requirements from the intermediate upgrade version
    $requirements = $this->requirements_service->loadRequirementsFromTag($effective_upgrade);
}
```

Uwaga: `loadRequirementsFromTag` czyta z `{tag}:upgrade/{tag}/requirements.json`.
Jeśli `$tag` = 4.3.1 i nie ma tam `upgrade/4.3.1/`, przejdzie na `{tag}:upgrade/4.3.0/requirements.json`
→ trzeba zmienić sygnaturę metody LUB dodać osobną metodę `loadRequirementsFromPath(tag, path)`.

#### Alternatywa dla requirements (prostsza):

Dodać do `UpgradeRequirementsService`:
```php
public function loadRequirements(string $tag, string $upgrade_version): ?array
{
    $path = "upgrade/{$upgrade_version}/requirements.json";
    $json = shell_exec("git show " . escapeshellarg("{$tag}:{$path}") . " 2>/dev/null");
    // ...
}
```
I wywołać: `loadRequirements($tag, $effective_upgrade)`.

### 3. State persistence

W `saveState` i `loadState` dodać pole `effective_upgrade`:
```json
{
  "tag": "4.3.1",
  "effective_upgrade": "4.3.0",
  "completed_steps": [...],
  ...
}
```
W `execute()` przy resume — odczytać `$effective_upgrade` z `$state['effective_upgrade'] ?? $tag`.

---

## Nowe pole w klasie Upgrade

```php
private array $intermediate_upgrade_error = [];
```

---

## Przykłady działania

| current | target | upgrade/ na tagu | effective | akcja |
|---------|--------|------------------|-----------|-------|
| 4.2.0 | 4.3.1 | [4.3.0, 4.3.1] | 4.3.1 | normalny flow |
| 4.2.0 | 4.3.1 | [4.3.0] | 4.3.0 | checkout 4.3.1, skrypty 4.3.0 |
| 4.2.0 | 4.3.1 | [] | 4.3.1 | checkout 4.3.1, brak skryptów (ok) |
| 4.2.0 | 4.3.2 | [4.3.0, 4.3.1] | null | ERROR: 4.2.0 → 4.3.0 → 4.3.1 → 4.3.2 |
| 4.3.0 | 4.3.2 | [4.3.0, 4.3.1] | 4.3.1 | checkout 4.3.2, skrypty 4.3.1 |

---

## Status implementacji

- [x] `UpgradeService::getUpgradeVersionsOnTag()` — nowa metoda
- [x] `UpgradeRequirementsService::loadRequirements(tag, upgrade_version)` — nowa metoda
- [x] `Upgrade::resolveEffectiveUpgradeVersion()` — nowa metoda
- [x] `Upgrade::execute()` — wywołanie resolvera + obsługa błędu 2+ pośrednich
- [x] `Upgrade::checkRequirements()` — użycie `loadRequirements(tag, effective_upgrade)`
- [x] `Upgrade::execute()` — przekazanie `$effective_upgrade` do pre/migrations/post kroków
- [x] `UpgradeService::saveState()` / `loadState()` — pole `effective_upgrade`
- [x] `Upgrade::execute()` — odczyt `effective_upgrade` z state przy resume
