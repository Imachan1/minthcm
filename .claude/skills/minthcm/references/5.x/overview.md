# MintHCM 5.x — specyfika wersji

## Vue 2 + Webpack

Frontend oparty na Vue 2, Webpack, Vuetify 2, Vuex.

- Komponenty: Options API (`.vue` z `export default { ... }`)
- Store: Vuex (`this.$store`)
- Build: `cd vue && npm run build`

Customizacje frontendowe trafiają do `vue/src/custom/`.

## API — Slim 4 (bez Doctrine ORM)

W 5.x brak Doctrine ORM — encje nie są auto-generowane z vardefs.
Dostęp do danych przez Sugar beans lub surowe zapytania.

- Routing: `api/app/Routes/`
- Kontrolery: `api/app/Controllers/`
- Customizacje: `api/custom/` (namespace: `MintHCM\Custom\Api\...`)

## PHP 7.4 / 8.0

Kompatybilność z PHP 7.4+. Unikaj składni wyłącznie z PHP 8.1+.
