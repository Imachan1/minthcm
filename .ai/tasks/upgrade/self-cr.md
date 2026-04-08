# Self Code Review: upgrade — Obsługa pośrednich upgrade'ów

**Data:** 2026-04-08
**Branch:** feature/upgrade_script
**Zakres:** 3 pliki PHP, diff HEAD vs working tree (zmiany z bieżącej sesji)
**Tryb review:** FOCUSED

## Kompletność planu

8/8 kroków zrealizowanych

| Krok | Status | Uwagi |
|------|--------|-------|
| `UpgradeService::getUpgradeVersionsOnTag()` | ✅ OK | git ls-tree, filtr regex |
| `UpgradeRequirementsService::loadRequirements(tag, upgrade_version)` | ✅ OK | `loadRequirementsFromTag` jako wrapper zachowany |
| `Upgrade::resolveEffectiveUpgradeVersion()` | ✅ OK | 0/1/2+ pośrednich poprawnie obsłużone |
| `Upgrade::execute()` — resolver + błąd 2+ pośrednich | ✅ OK | null check, steps_path, early return |
| `Upgrade::checkRequirements()` — nowy param `$effective_upgrade` | ✅ OK | |
| `Upgrade::execute()` — `$effective_upgrade` do pre/migrations/post | ✅ OK | `fetch_and_checkout` nadal z `$tag` |
| `UpgradeService::saveState()` — pole `effective_upgrade` | ✅ OK | fallback `?: $tag` gdy pusty string |
| `Upgrade::execute()` — resume z `$state['effective_upgrade']` | ✅ OK | backward-compatible przez `??` |

## Znalezione problemy

### CRITICAL

*(brak)*

### WARNING

*(brak)*

### INFO

- `legacy/MintCLI/src/Services/UpgradeService.php:37` — **[Standards] Wyrównanie kluczy** — `'effective_upgrade' =>` nie ma spacji przed `=>`, podczas gdy pozostałe klucze mają wyrównanie do kolumny. Dodaj jedną spację: `'effective_upgrade'  =>`.

- `legacy/MintCLI/src/Commands/Upgrade.php:322` — **[Standards] Callback jako string** — `usort($intermediates, 'version_compare')` przekazuje nazwę funkcji jako string. Reszta kodu używa closures. Rozważ: `usort($intermediates, fn($a, $b) => version_compare($a, $b))`.

## Podsumowanie

Logika obsługi pośrednich upgrade'ów jest poprawna — przetestowałem manualnie wszystkie 5 scenariuszy z tabeli w planie i wyniki są zgodne z oczekiwaniami. Szczególnie poprawnie obsłużony edge case `current=4.3.0, target=4.3.2` (4.3.0 nie jest liczone jako pośredni przez `version_compare` z `>`).

Warto odnotować znane ograniczenie architectury: `resolveEffectiveUpgradeVersion` działa przed `git fetch`. Jeśli tag istnieje tylko na remote, metoda zwróci `$tag` (ścieżka "0 intermediates"). Jest to spójna degradacja — identyczna jak istniejący `loadRequirementsFromTag`.

Resume backward compatibility poprawna — stary state bez klucza `effective_upgrade` poprawnie fallbackuje przez `??`.

**Status: READY FOR CR**
