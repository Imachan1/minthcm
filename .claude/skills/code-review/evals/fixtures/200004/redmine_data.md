# Dane Redmine — Zagadnienie #200004

**ID:** 200004
**Tytuł:** Dodanie helperów formatowania i parsowania cen
**Tracker:** User Story
**Tracker ID:** 18
**Projekt:** test-shop
**Status:** W toku
**Priorytet:** Normalny
**Opis:**
Jako deweloper chcę mieć wspólne utility functions do formatowania i parsowania cen w całej aplikacji.

Kryteria akceptacji:
- `formatPrice(float $amount, string $currency = 'PLN'): string` — formatuje kwotę z 2 miejscami po przecinku, separatorem tysięcy (spacja), przecinkiem dziesiętnym
- Ujemne kwoty formatowane z myślnikiem przed liczbą (np. `-1 234,56 PLN`)
- `parsePrice(string $price): float` — parsuje string z powrotem do float
- Funkcje dostępne globalnie przez require helpers.php

**Parent issue:** brak
