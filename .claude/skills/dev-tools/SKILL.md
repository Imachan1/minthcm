---
name: dev-tools
version: 1.0.0
description: "Narzędzia deweloperskie — generowanie UUID i inne utility przydatne podczas wdrożeń i prac w projekcie."
---

# Dev Tools

## Kiedy używać

Gdy potrzebujesz:
- wygenerować jeden lub więcej UUID v4 (klucze rekordów, seed danych, migracje)

## Kiedy nie używać

Gdy system/framework posiada wbudowany generator UUID — korzystaj z natywnego mechanizmu.

## Dostępne skrypty

```bash
# Generuj 1 UUID
python .claude/skills/dev-tools/scripts/uuid-gen.py

# Generuj N UUID (np. 5)
python .claude/skills/dev-tools/scripts/uuid-gen.py 5
```
