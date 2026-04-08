# Upgrade Plan: MintHCM 4.2 → 4.3.0

## Overview

This document describes the upgrade plan from MintHCM 4.2.0 to 4.3.0.
The upgrade is managed by `legacy/MintCLI/src/Commands/Upgrade.php` + `UpgradeService.php`.
All files for this upgrade live in `upgrade/4.3.0/`.

---

## Directory Structure

```
upgrade/4.3.0/
├── requirements.json              ← Environment checks before upgrade
├── pre_upgrade/
│   ├── 01_check_oauth2_dir.php    ← Warn about upcoming OAuth2 key generation
│   └── 02_backup_reminder.php     ← Remind user to verify backup
├── post_upgrade/
│   ├── 01_generate_oauth2_keys.php ← Generate OAuth2 private/public keys + client secret
│   ├── 02_instance_rebuild.php    ← instance:rebuild (Repair, JS, cache, OAuth2 perms)
│   └── 03_elasticsearch_reindex.php ← Reindex all data in Elasticsearch
└── migrations/
    └── (empty — schema changes handled by instance:rebuild / Quick Repair)
```

---

## Key Changes in 4.3.0 That Require Upgrade Actions

### Critical
1. **OAuth2 Keys** — New OAuth2 implementation requires private/public key pair.
   - Keys must be generated after checkout: `./MintCLI oauth2:create-keys`
   - Client secret must be regenerated: `./MintCLI oauth2:regenerateClientSecret`
   - Without keys, frontend authentication will fail.

2. **instance:rebuild** — New CLI command introduced in 4.3.0 that replaces manual repair.
   - Runs RepairAndRebuildLegacy, JS rebuild, clears api/cache, fixes OAuth2 permissions.
   - Required due to 200+ Doctrine entity changes, Doctrine cache invalidation, new modules.

3. **Elasticsearch reindex** — Required after any major upgrade to ensure search works correctly.

### Dependencies (automated via `composer install` + `npm install`)
- `league/oauth2-server ^8.5` (new Composer dep)
- `vite ^6`, `vuetify ^3.11.3` (stable), `tinymce ^7`, etc. (handled by npm)
- Node.js **21** strictly required (was `~16 || ~21`)

### No SQL Migrations Needed
- New tables/columns (OAuth2, entities, mobile device tokens) are created
  automatically by Quick Repair and Rebuild (via `instance:rebuild`).

---

## requirements.json

- PHP: `8.2` (min) – `8.3` (max)
- MySQL: `8.0` min
- Elasticsearch: `7.10` – `7.16`
- Node.js: `21` — weryfikowany przez nowy checker w `UpgradeRequirementsService`, **warn only** (nie blokuje)
- Disk: `3072 MB`
- Upgradeable from: `4.2.*` (wildcard — obsługiwane przez `UpgradeRequirementsService`)

---

## Pre-Upgrade Scripts

| Plik | Cel |
|------|-----|
| `01_check_oauth2_dir.php` | Sprawdź czy katalog kluczy OAuth2 istnieje; informuj że klucze zostaną wygenerowane po checkout |
| `02_backup_reminder.php` | Ostatnie przypomnienie o weryfikacji backupu |

## Post-Upgrade Scripts

| Plik | Cel | Błąd |
|------|-----|------|
| `01_generate_oauth2_keys.php` | `oauth2:create-keys` + `oauth2:regenerateClientSecret` — **tylko jeśli kluczy nie ma** (idempotentny) | FAIL |
| `02_instance_rebuild.php` | `instance:rebuild` — repair legacy, JS rebuild, clear api/cache, OAuth2 permissions | FAIL |
| `03_elasticsearch_reindex.php` | `elasticsearch:reindex` — reindeksuje gdy indeksy istnieją lub gdy ich nie ma; **warn only** w razie błędu | WARN |

## Zmiany w UpgradeRequirementsService

Dodanie nowej metody `checkNodeVersion()` weryfikującej `node --version`:
- Jeśli node nie istnieje lub wersja < 21: **warning**, upgrade **nie jest blokowany**
- Pole w `requirements.json`: `"node": { "min": "21" }`

---

## Implementation Status

- [x] `upgrade/4.3.0/requirements.json`
- [x] `upgrade/4.3.0/pre_upgrade/01_check_oauth2_dir.php`
- [x] `upgrade/4.3.0/pre_upgrade/02_backup_reminder.php`
- [x] `upgrade/4.3.0/post_upgrade/01_generate_oauth2_keys.php`
- [x] `upgrade/4.3.0/post_upgrade/02_instance_rebuild.php`
- [x] `upgrade/4.3.0/post_upgrade/03_elasticsearch_reindex.php`
- [x] `UpgradeRequirementsService.php` — `checkNodeVersion()` (warn only) + wildcard + `getWarnings()` + `getSkipSteps()`
- [x] `Upgrade.php` — obsługa `skip_steps` + wyświetlanie warnings z serwisu
