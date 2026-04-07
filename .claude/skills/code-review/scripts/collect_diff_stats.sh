#!/usr/bin/env bash
# collect_diff_stats.sh <DIFF_BASE> <DIFF_HEAD>
#
# Zbiera statystyki diffu: lista plików, numstat, rozmiary, rekomendowany tryb review.
# Zastępuje ~4 osobne komendy z Step 8a–8b.
# Output: JSON na stdout

DIFF_BASE="${1}"
DIFF_HEAD="${2}"
if [ -z "$DIFF_BASE" ] || [ -z "$DIFF_HEAD" ]; then
  printf '{"error":"missing_args","message":"Użycie: collect_diff_stats.sh <DIFF_BASE> <DIFF_HEAD>"}\n'
  exit 1
fi

# Sprawdź czy diff nie jest pusty
CHANGED_FILES=$(git diff "${DIFF_BASE}..${DIFF_HEAD}" --name-only 2>/dev/null)
if [ -z "$CHANGED_FILES" ]; then
  printf '{"error":"empty_diff","message":"Brak zmian do przeglądu."}\n'
  exit 0
fi

# Numstat: insertions<TAB>deletions<TAB>filepath
NUMSTAT=$(git diff "${DIFF_BASE}..${DIFF_HEAD}" --numstat 2>/dev/null)

# Łączna liczba zmienionych linii (pomijamy pliki binarne gdzie insertions="-")
TOTAL_LINES=$(printf '%s\n' "$NUMSTAT" | awk '$1 != "-" {total += $1 + $2} END {print total+0}')

# Tryb review na podstawie progu
if [ "$TOTAL_LINES" -ge 500 ]; then
  REVIEW_MODE="chunked"
elif [ "$TOTAL_LINES" -ge 80 ]; then
  REVIEW_MODE="parallel"
else
  REVIEW_MODE="sequential"
fi

# Zliczenia wg kategorii
VENDOR_COUNT=0
BINARY_COUNT=0
REVIEWABLE_COUNT=0

FILES_JSON="["
FIRST=true

while IFS=$'\t' read -r insertions deletions filepath; do
  [ -z "$filepath" ] && continue

  # Plik binarny (insertions = "-")
  if [ "$insertions" = "-" ] || [ "$deletions" = "-" ]; then
    IS_BINARY="true"
    insertions=0
    deletions=0
    DIFF_LINES=0
    FILE_LINES=0
    CONTEXT_STRATEGY="skip"
    BINARY_COUNT=$((BINARY_COUNT + 1))
  else
    IS_BINARY="false"
    DIFF_LINES=$((insertions + deletions))
    FILE_LINES=$(wc -l < "$filepath" 2>/dev/null | tr -d ' ') || FILE_LINES=0
    CONTEXT_STRATEGY="extended_diff"
  fi

  # Vendor / generated — sprawdź ścieżkę i rozszerzenie
  IS_VENDOR="false"
  case "$filepath" in
    vendor/*|node_modules/*|bower_components/*)
      IS_VENDOR="true"; CONTEXT_STRATEGY="skip"; VENDOR_COUNT=$((VENDOR_COUNT + 1));;
    composer.lock|package-lock.json|yarn.lock)
      IS_VENDOR="true"; CONTEXT_STRATEGY="skip"; VENDOR_COUNT=$((VENDOR_COUNT + 1));;
    *.min.js|*.min.css)
      IS_VENDOR="true"; CONTEXT_STRATEGY="skip"; VENDOR_COUNT=$((VENDOR_COUNT + 1));;
  esac

  # Strategia kontekstu dla plików reviewowalnych
  if [ "$IS_BINARY" = "false" ] && [ "$IS_VENDOR" = "false" ]; then
    REVIEWABLE_COUNT=$((REVIEWABLE_COUNT + 1))
    # Reguła z Step 8a:
    #   plik < 150 linii LUB diff > 50 linii → pełny plik
    #   pozostałe → rozszerzony diff (-U30)
    if [ "$FILE_LINES" -lt 150 ] || [ "$DIFF_LINES" -gt 50 ]; then
      CONTEXT_STRATEGY="full_file"
    fi
  fi

  filepath_esc=$(printf '%s' "$filepath" | sed 's/\\/\\\\/g' | sed 's/"/\\"/g')

  if [ "$FIRST" = true ]; then
    FIRST=false
  else
    FILES_JSON="${FILES_JSON},"
  fi
  FILES_JSON="${FILES_JSON}{\"path\":\"${filepath_esc}\",\"insertions\":${insertions:-0},\"deletions\":${deletions:-0},\"diff_lines\":${DIFF_LINES},\"file_lines\":${FILE_LINES},\"is_binary\":${IS_BINARY},\"is_vendor\":${IS_VENDOR},\"context_strategy\":\"${CONTEXT_STRATEGY}\"}"

done <<< "$NUMSTAT"

FILES_JSON="${FILES_JSON}]"

printf '{"total_lines":%s,"review_mode":"%s","reviewable_count":%s,"vendor_count":%s,"binary_count":%s,"files":%s}\n' \
  "$TOTAL_LINES" \
  "$REVIEW_MODE" \
  "$REVIEWABLE_COUNT" \
  "$VENDOR_COUNT" \
  "$BINARY_COUNT" \
  "$FILES_JSON"
