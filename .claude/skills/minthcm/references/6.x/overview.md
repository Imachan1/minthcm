# MintHCM 6.x — specyfika wersji

## Vue 3 + Vite

Frontend oparty na Vue 3 (Composition API), Vite, Vuetify 3, Pinia, TypeScript.

- Komponenty: `.vue` z `<script setup lang="ts">`
- Store: Pinia (`defineStore`)
- Build: `cd vue && npm run build:repo`

Customizacje frontendowe trafiają do `vue/src/custom/`.

## API — Slim 4 + Doctrine ORM

- Routing: `api/app/Routes/`
- Kontrolery: `api/app/Controllers/` (extend `BaseController`)
- Encje: Doctrine ORM, **auto-generowane** z vardefs — nie edytuj ręcznie
- Customizacje: `api/custom/` (namespace: `MintHCM\Custom\Api\...`)

## PHP 8.2

Wymagane: PHP 8.2+. Używaj typed properties, match expressions, named arguments tam gdzie poprawiają czytelność.
