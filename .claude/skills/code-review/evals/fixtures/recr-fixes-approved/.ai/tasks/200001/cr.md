# Code Review: #200001 — Dodanie kalkulatora cen dla koszyka zakupowego

**Data:** 2026-03-20
**Branch:** feature/200001
**Zakres:** 1 commit(ów): HEAD~1..HEAD
**Reviewer:** AI (Claude Code)
**Redmine:** https://redmine.evolpe.net/issues/200001

## Kontekst zagadnienia

Dodanie kalkulatora cen dla koszyka zakupowego. Kryteria: calculateTotal oblicza sumę cen wszystkich elementów koszyka, formatPrice formatuje kwotę z 2 miejscami po przecinku.

## Zmienione pliki

- src/cart.php (+12/-1)

## Znalezione problemy

### CRITICAL

(brak)

### WARNING

- [ ] `src/cart.php:8` — **[Błędy logiczne] Off-by-one w pętli** — warunek `$i <= count($items)` powoduje dostęp do indeksu poza tablicą przy ostatniej iteracji (PHP zwróci null, suma będzie błędna lub notice) → zmień na `$i < count($items)`

### INFO

(brak)

## Podsumowanie

Kod implementuje kalkulator cen, ale zawiera błąd off-by-one w pętli for. Funkcja formatPrice działa poprawnie. Wymagana poprawka przed merge.

**Werdykt:** CHANGES REQUESTED
