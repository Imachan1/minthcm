#!/usr/bin/env bash
# load_previous_findings.sh <ISSUE_ID>
#
# Uruchamiaj na początku Step R1 (re-review).
# Parsuje cr.md i zwraca niezatwierdzone findings (CRITICAL/WARNING) jako JSON.
# Automatycznie wykrywa rundę — jeśli są sekcje ## Re-CR, parsuje z ostatniej.
# Output: JSON na stdout

ISSUE_ID="${1}"
if [ -z "$ISSUE_ID" ]; then
  printf '{"error":"missing_issue_id","message":"Podaj numer zagadnienia jako argument."}\n'
  exit 1
fi

CR_FILE=".ai/tasks/${ISSUE_ID}/cr.md"

if [ ! -f "$CR_FILE" ]; then
  printf '{"error":"cr_not_found","message":"Nie znaleziono raportu CR: .ai/tasks/%s/cr.md"}\n' "$ISSUE_ID"
  exit 0
fi

# Policz istniejące sekcje ## Re-CR — wyznacza numer bieżącej rundy
RECR_COUNT=$(grep -c "^## Re-CR" "$CR_FILE" 2>/dev/null)
RECR_COUNT="${RECR_COUNT:-0}"
RECR_ROUND=$((RECR_COUNT + 1))

# Ustal linię startową sekcji do parsowania
if [ "$RECR_COUNT" -eq 0 ]; then
  # Pierwsza runda — parsuj oryginalny CR (cały plik)
  SECTION_START=1
else
  # Kolejna runda — parsuj tylko ostatnią sekcję ## Re-CR
  SECTION_START=$(grep -n "^## Re-CR" "$CR_FILE" | tail -1 | cut -d: -f1)
  SECTION_START="${SECTION_START:-1}"
fi

# Parsuj niezatwierdzone checkboxy (- [ ]) z sekcji CRITICAL i WARNING
# Pomija zatwierdzone (- [x]) i sekcję INFO
FINDINGS_RAW=$(tail -n "+${SECTION_START}" "$CR_FILE" | awk '
  /^### CRITICAL/{severity="CRITICAL"; in_section=1; next}
  /^### WARNING/{severity="WARNING"; in_section=1; next}
  /^###/{in_section=0; next}
  /^##/{in_section=0; next}
  in_section && /^- \[ \] /{print severity "|" substr($0, 7)}
')

# Buduj JSON array z parsowanych findings
FINDINGS_JSON="["
FIRST=true
INDEX=0

while IFS='|' read -r severity line_content; do
  [ -z "$severity" ] && continue

  # Wyciągnij plik:linia — tekst przed pierwszym " — " (em dash z spacjami), usuń backticki
  FILE_LINE=$(printf '%s' "$line_content" | sed 's/ — .*//' | tr -d '`')
  FILE_PATH=$(printf '%s' "$FILE_LINE" | cut -d: -f1)
  LINE_NUM=$(printf '%s' "$FILE_LINE" | cut -d: -f2 | tr -d '[:space:]')

  # Opis — tekst po " — " (bez sugestii po " → " i confidence na końcu)
  # Obsługuje oba formaty: *(confidence: XX)* i [confidence: XX]
  DESCRIPTION=$(printf '%s' "$line_content" | grep -o ' — .*' | sed 's/ — //' | sed 's/ → .*//' | sed 's/ *(confidence: [0-9]*)\*//' | sed 's/ \[confidence: [0-9]*\]//')

  # Escape dla JSON
  FILE_PATH_ESC=$(printf '%s' "$FILE_PATH" | sed 's/\\/\\\\/g; s/"/\\"/g')
  LINE_NUM_ESC=$(printf '%s' "$LINE_NUM" | tr -cd '0-9')
  DESCRIPTION_ESC=$(printf '%s' "$DESCRIPTION" | sed 's/\\/\\\\/g; s/"/\\"/g')
  SEVERITY_ESC=$(printf '%s' "$severity" | sed 's/"/\\"/g')

  if [ "$FIRST" = true ]; then
    FIRST=false
  else
    FINDINGS_JSON="${FINDINGS_JSON},"
  fi

  FINDINGS_JSON="${FINDINGS_JSON}{\"index\":${INDEX},\"severity\":\"${SEVERITY_ESC}\",\"file\":\"${FILE_PATH_ESC}\",\"line\":\"${LINE_NUM_ESC}\",\"description\":\"${DESCRIPTION_ESC}\"}"
  INDEX=$((INDEX + 1))
done <<< "$FINDINGS_RAW"

FINDINGS_JSON="${FINDINGS_JSON}]"

printf '{"recr_round":%s,"previous_findings_count":%s,"findings":%s}\n' \
  "$RECR_ROUND" \
  "$INDEX" \
  "$FINDINGS_JSON"
