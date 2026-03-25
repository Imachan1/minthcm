---
name: bug-report
description: "Tworzenie zgłoszeń bugów bezpośrednio w Redmine przez MCP. Użyj tego skilla zawsze gdy użytkownik chce zgłosić buga, błąd, defekt, problem w aplikacji, raportować nieprawidłowe działanie, lub wspomina o 'bug report', 'zgłoszenie błędu', 'coś nie działa', 'znalazłem buga'. Skill tworzy ustrukturyzowane zgłoszenie z krokami reprodukcji, środowiskiem, oczekiwanym/rzeczywistym wynikiem i wysyła je do Redmine. Używaj tego skilla nawet jeśli użytkownik opisuje problem nieformalnie — pomóż mu przekształcić opis w profesjonalne zgłoszenie."
---

# Zgłaszanie bugów w Redmine

Skill do tworzenia profesjonalnych zgłoszeń błędów bezpośrednio w Redmine przez serwer MCP.

## Kiedy używać

- Użytkownik mówi, że coś nie działa, znalazł buga, chce zgłosić błąd
- Użytkownik opisuje nieprawidłowe zachowanie aplikacji (nawet nieformalnie)
- Użytkownik prosi o stworzenie bug reportu, zgłoszenia, defektu
- Test automatyczny lub manualny wykrył błędy

## Kluczowa zasada: jedno zgłoszenie per test

Jeśli w jednym teście (np. test modułu Kandydaci) wykryto **wiele bugów** — **NIE twórz osobnego zgłoszenia dla każdego buga**. Utwórz **jedno zgłoszenie** z listą wszystkich wykrytych problemów. W tytule użyj nazwy testu, a w opisie wylistuj bugi z ich krokami reprodukcji.

## Przepływ pracy

### 1. Zbierz informacje od użytkownika

Zanim stworzysz zgłoszenie, upewnij się że masz następujące dane. Jeśli brakuje którejś — zapytaj.

**Wymagane:**
- Projekt w Redmine (project_id) — zapytaj jeśli nie wynika z kontekstu
- Tytuł buga — krótki, konkretny opis problemu (max 10 słów). Jeśli wiele bugów z jednego testu — użyj nazwy testu, np. "Bugi z testu modułu Kandydaci"
- Kroki reprodukcji — co trzeba zrobić żeby odtworzyć błąd
- Wynik oczekiwany — co powinno się stać
- Wynik rzeczywisty — co się faktycznie dzieje

**Opcjonalne (mają wartości domyślne):**
- Typ trackera — domyślnie "Błąd" (id: 1). Dostępne:
  - **Błąd** (id: 1) — standardowy bug
  - **Błąd krytyczny** (id: 15) — blokuje pracę, utrata danych
  - **Błąd niekrytyczny** (id: 16) — drobne problemy, kosmetyka
  - **User Story Bug** (id: 19) — bug powiązany z user story
- Priorytet — domyślnie "Normalny" (id: 4). Dostępne:
  - Niski (id: 3)
  - Normalny (id: 4)
  - Wysoki (id: 5)
  - Pilny (id: 6)
  - Na wczoraj (id: 7)
- Środowisko (przeglądarka, URL, wersja CRM)
- Screenshoty / załączniki
- Zadanie nadrzędne (parent_issue_id)
- Przypisany (assigned_to_id)

### 2. Doprecyzuj jeśli opis jest niepełny

Jeśli użytkownik opisuje buga nieformalnie (np. "hej, lista kandydatów się nie ładuje"), pomóż mu ustrukturyzować opis. Zapytaj o:
- W jakiej przeglądarce i na jakim URL to się dzieje?
- Jakie kroki prowadzą do problemu?
- Czy to zawsze się powtarza, czy sporadycznie?
- Czy jest jakiś komunikat błędu?

Nie wymagaj perfekcyjnego opisu — lepiej zgłosić buga z tym co wiemy niż nie zgłosić wcale.

### 3. Sformatuj opis zgłoszenia

Użyj poniższego szablonu do pola `description` w Redmine. Redmine używa formatowania Textile.

**Szablon dla pojedynczego buga:**

```textile
h3. Środowisko

* *Przeglądarka:* [np. Chrome 120, Firefox 121]
* *URL:* [adres strony gdzie występuje błąd]
* *Platforma CRM:* [np. MintHCM, SpiceCRM, SuiteCRM]
* *Instancja:* [np. contrainv4.int1.evolpe.net/develop_serwis]

h3. Wykonane kroki

# [Krok 1]
# [Krok 2]
# [Krok 3]

h3. Wynik rzeczywisty

[Co się faktycznie dzieje]

h3. Wynik oczekiwany

[Co powinno się stać]

h3. Dodatkowe informacje

[Komunikaty błędów, logi konsoli, obserwacje, częstotliwość występowania — jeśli brak, usuń tę sekcję]

h3. Załączniki

[Lista screenshotów lub plików — jeśli brak, usuń tę sekcję]
```

**Szablon dla wielu bugów z jednego testu:**

```textile
h3. Środowisko

* *Przeglądarka:* [np. Chrome 120]
* *URL:* [adres instancji]
* *Platforma CRM:* [np. MintHCM]
* *Instancja:* [np. contrainv4.int1.evolpe.net/develop_serwis]

---

h3. Bug 1: [Krótki opis problemu]

*Sekcja testu:* [np. Tworzenie rekordu]

*Wykonane kroki:*
# [Krok 1]
# [Krok 2]

*Wynik rzeczywisty:* [Co się faktycznie dzieje]
*Wynik oczekiwany:* [Co powinno się stać]

---

h3. Bug 2: [Krótki opis problemu]

*Sekcja testu:* [np. ListView — filtrowanie]

*Wykonane kroki:*
# [Krok 1]
# [Krok 2]

*Wynik rzeczywisty:* [Co się faktycznie dzieje]
*Wynik oczekiwany:* [Co powinno się stać]

---

h3. Załączniki

[Lista screenshotów — jeśli brak, usuń tę sekcję]
```

Ważne zasady formatowania:
- Sekcję "Środowisko" wypełnij na podstawie tego co wiesz o projekcie użytkownika (platforma CRM, adres instancji). Nie wymuszaj podania wszystkich danych — wpisz co wiesz, resztę oznacz jako "do uzupełnienia".
- Sekcję "Dodatkowe informacje" wypełnij tylko jeśli użytkownik podał ekstra kontekst. Jeśli nie — usuń sekcję.
- Sekcję "Załączniki" wypełnij tylko gdy użytkownik wspomni o screenshotach. Jeśli nie — usuń sekcję.

### 4. Wyślij do Redmine

Użyj odpowiedniego API call z sekcji "Gdzie zgłaszamy buga" poniżej — wybierz wariant w zależności od kontekstu (projekt wdrożeniowy vs serwis).

### 5. Potwierdź użytkownikowi

Po utworzeniu zgłoszenia wyświetl:
- Numer zgłoszenia (np. #12345)
- Link do zgłoszenia w Redmine
- Krótkie podsumowanie: tytuł, tracker, priorytet, projekt

Zaproponuj: "Chcesz coś zmienić w zgłoszeniu? Mogę je zaktualizować."

## Gdzie zgłaszamy buga

Zależy od kontekstu projektu:

| Kontekst | Gdzie zgłosić | Tracker | Szczegóły API |
|----------|---------------|---------|---------------|
| **Projekt wdrożeniowy — BUG pod US** | Pod-zagadnienie w User Story | tracker_id: **24** | category_id: **813**, estimated_hours: **0**, prefiks w tytule: **BUG:** |
| **Serwis** | Pod zagadnieniem "Mały rozwój" | Błąd (id: 1) | — |
| **Błąd krytyczny / niekrytyczny zgłoszony przez klienta** | Osobne zagadnienie (jak Mały rozwój) | Błąd krytyczny (id: 15) lub Błąd niekrytyczny (id: 16) | — |

> **Uwaga:** Błąd krytyczny i Błąd niekrytyczny to trackery używane gdy **klient zgłasza buga**. Tester wewnętrzny w projekcie wdrożeniowym używa trackera id **24** z prefiksem **BUG:** w tytule.

### Przykład API call — BUG pod US (projekt wdrożeniowy)

```
path: /issues.json
method: post
data:
  issue:
    project_id: [id projektu]
    tracker_id: 24
    category_id: 813
    priority_id: [id priorytetu, domyślnie 4]
    estimated_hours: 0
    subject: "BUG: [Opis problemu]"
    description: "[Sformatowany opis — szablon powyżej]"
    parent_issue_id: [id User Story pod którym zgłaszamy]
```

### Przykład API call — BUG w serwisie

```
path: /issues.json
method: post
data:
  issue:
    project_id: [id projektu]
    tracker_id: 1
    priority_id: [id priorytetu, domyślnie 4]
    subject: "[Opis problemu]"
    description: "[Sformatowany opis — szablon powyżej]"
    parent_issue_id: [id zagadnienia "Mały rozwój"]
```

## Dodawanie załączników

Jeśli użytkownik chce dołączyć plik (screenshot, log), użyj dwustepowego procesu:

1. **Upload pliku** — przez `redmine_upload` z parametrem `file_path`
2. **Powiąż z zgłoszeniem** — dodaj token uploadu do tablicy `uploads` w danych zgłoszenia:

```
data:
  issue:
    ...
    uploads:
      - token: "[token z uploadu]"
        filename: "screenshot.png"
        content_type: "image/png"
        description: "Screenshot błędu"
```

## Wskazówki dotyczące jakości zgłoszeń

Dobry tytuł buga to taki, który mówi CO jest nie tak i GDZIE:
- ✅ "Lista kandydatów nie ładuje się po filtracji"
- ✅ "Błąd 500 przy zapisie umowy bez daty"
- ✅ "Bugi z testu modułu Kandydaci" (gdy wiele bugów z jednego testu)
- ❌ "Nie działa"
- ❌ "Problem z formularzem"

Pomagaj użytkownikowi poprawić tytuł jeśli jest zbyt ogólny — zaproponuj lepszą wersję.

## Odnośniki do istniejących zgłoszeń

Przed utworzeniem nowego buga rozważ szybkie sprawdzenie czy podobny bug już nie istnieje:

```
path: /issues.json
method: get
params:
  project_id: [id projektu]
  tracker_id: 1,15,16,19
  status_id: open
  subject: [słowa kluczowe z tytułu]
```

Jeśli znajdziesz potencjalny duplikat — poinformuj użytkownika i zapytaj czy chce kontynuować.
