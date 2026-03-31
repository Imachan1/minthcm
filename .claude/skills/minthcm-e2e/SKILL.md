---
name: minthcm-e2e
version: 1.1.0
description: "Skill do testów E2E specyficznych dla platformy MintHCM. Użyj RAZEM z e2e-testing (common). Zawiera specyfikę: iframe EditView, Vue.js triggery, selektory MintHCM, toast messages, auto-prefix telefonu, dashlety. Trigger: test MintHCM, iframe, frameLocator, legacy iframe, mint-status-box, EditView test, DetailView test, Vue fill, MintHCM formularz, dashlet."
---

# MintHCM E2E Testing

Skill specyficzny dla testowania platformy MintHCM. **Używaj zawsze razem z `e2e-testing` (common)** — ten skill nie powtarza ogólnych konwencji, tylko dodaje specyfikę platformy.

## Kiedy używać

- Piszesz test E2E w projekcie opartym o MintHCM
- Tworzysz Page Object dla formularza MintHCM (EditView)
- Musisz obsłużyć iframe, Vue.js triggery lub toast messages
- Debugujesz problemy z selektorami w MintHCM
- Testujesz dashlety

## Kiedy NIE używać

- Test na innej platformie (SpiceCRM, SuiteCRM) — użyj odpowiedniego skilla systemowego E2E
- Ogólne pytania o Playwright, POM, faker — użyj `e2e-testing` (common)
- Implementacja w MintHCM (nie test) — użyj `minthcm-project` lub `minthcm-core`

## Identyfikacja wersji

Sprawdź `system_version` w `.claude/skills-manifest.yaml`. Na tej podstawie ładuj odpowiednie referencje.

## Routing

**Krok 1** — zawsze zacznij od referencji wspólnej:

| Plik | Zawartość |
|------|-----------|
| `references/common.md` | Iframe pattern, Vue.js triggery, toast messages, ogólne zasady MintHCM |
| `references/dashlets.md` | Checklist testowania dashletów (MintHCM / SuiteCRM) |

**Krok 2** — załaduj referencję per wersja:

| Wersja | Plik |
|--------|------|
| MintHCM 5.x | `references/5.x/selectors.md` |
| MintHCM 6.x | `references/6.x/selectors.md` |

## Kluczowe zasady (skrót)

- Formularze EditView w MintHCM żyją w **iframe** — wszystkie lokatory muszą przechodzić przez `page.frameLocator('iframe')`
- MintHCM używa **Vue.js** — pola wypełniaj przez `fill()` (nie `type()`), a po datach naciśnij `Tab`
- Toast messages (sukces/błąd) są **poza iframe** — lokuj je przez `page.locator()`, nie `iframeLocator`
- Przed pisaniem POM **zawsze sprawdź DOM w DevTools** — nie zakładaj struktury UI
