---
name: skills-sync
version: 0.0.2
temp: true
temp_note: "Tymczasowy stub do testowania init-project.sh. Zastąpić docelową implementacją w Fazie 2."
description: "[TEMP] Meta-skill do zarządzania skillami — synchronizacja z głównym repo evolpe-skills."
---

> [!WARNING]
> **TYMCZASOWY SKILL — tylko do testów mechaniki init-project.sh**
> Nie zawiera docelowej logiki. Zastąpić implementacją z Fazy 2 planu.

# Skills Sync

## Kiedy używać

Gdy chcesz:
- sprawdzić czy są aktualizacje skilli (`check-updates.sh`)
- pobrać aktualizacje skilli z głównego repo (`sync.sh --pull`)
- wyeksportować lokalne zmiany skilla do głównego repo (`sync.sh --push <nazwa>`)

## Kiedy nie używać

Przy tworzeniu nowych skilli — użyj `skill-creator`.

## Dostępne skrypty

```bash
# Sprawdź dostępne aktualizacje
.claude/skills/skills-sync/scripts/check-updates.sh

# Pobierz aktualizacje (respektuje local_changes: true)
.claude/skills/skills-sync/scripts/sync.sh --pull

# Wyślij lokalne zmiany skilla do głównego repo
.claude/skills/skills-sync/scripts/sync.sh --push <nazwa-skilla>
```

## Uwagi

- Skille z `local_changes: true` w `skills-manifest.yaml` nie są nadpisywane przy `--pull`
- Aby wymusić nadpisanie: `sync.sh --pull --force`
- Plik `skills-manifest.yaml` jest jedynym źródłem prawdy — `source.ref` określa wersję zestawu skilli
