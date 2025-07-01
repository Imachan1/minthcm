# Mint MCP

Mint MCP to moduł w Mint, który jest serwerem MCP i umożliwia integrację z klientami MCP, takimi jak Visual Studio Code, Copilot Chat itp. Dzięki temu możemy korzystać z narzędzi MCP w Mint oraz dodawać swoje własne.

## Konfiguracja serwera
### .htaccess

Dodajemy następującą regułę:

```apache
# --- MCP endpoint ---
RewriteRule ^mcp/?$ mcp/index.php [L]
RewriteRule ^mcp/index.php$ mcp/index.php [L]
```

### Generowanie access tokena

Do autoryzacji używamy standardowych tokenów z api V8

Upewniamy się że mamy wygenrowane klucze w folderze:
```bash
cd legacy/Api/V8/OAuth2
```
Jeżeli nie mamy kluczy to generujemy je poleceniem:
```bash
openssl genrsa -out private.key 2048;openssl rsa -in private.key -pubout -out public.key;sudo chmod 600 private.key public.key;sudo chown www-data:www-data p*.key
```

#### Dodawanie klienta OAuth2
2. W Mint dodajemy klienta OAuth2:

`Administrator -> Administration -> OAuth2 Clients and Tokens`

3. Wybieramy "New Password Client"
4. Za pomocą DevTools robimy usuwamy display: none; z pola z hasłem i wpisujemy hasło (np. qwerty123) i zapisujemy

#### Generowanie access tokena
1. Za pomocą curl albo Postman wykonujemy zapytanie POST do endpointu:

```bash
POST "https://your-mint-domain/legacy/Api/access_token"

{
  "grant_type": "password",
  "client_id": "e7e62cbb-a03d-31d1-d90f-685929bf19ed", # zmienić na swoje client_id
  "client_secret": "qwerty123", # hasło z klienta
  "scope": "",
  "username": "admin",
  "password": "qwerty" # hasło do konta admin
}
```

W odpowiedzi otrzymamy access token:

```json
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI.....",
    "refresh_token": "def50200eb5536b90e123adc36ad962e1b0b9341d10bb69d7630f...."
}
```

2. Bierzemy access_token i używamy go w nagłówku Authorization w zapytaniach do MCP.

## Konfiguracja klienta MCP

Poniższy przykład opisuje configurację dla klienta MCP w Visual Studio Code, podobnie powinno wyglądać dla innych klientów.

1. Otwieramy ustawienia w VsCode (komenda Open User Settings (JSON))
2. Dodajemy nowy serwer MCP do sekcji `mcp`:
```json
"mcp": {
    "servers": {
        "my-mcp-server-mint1": {
            "type": "http",
            "url": "https://your-mint-domain/mcp/index.php",
            "headers": {
                "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJ....."
            }
        }
    }
}
```
3. Wykonujemy komendę `MCP: List Servers` i wybieramy nasz serwer `my-mcp-server-mint1`.
4. Wybieramy `Start server` i czekamy na komunikat `Connection state: Running` a następnie `Discovered X tools`.
5. Otwieramy Copilot Chat, ustawiamy mode na `Agent` i wybieramy `"Configure tools..."` (ikona narzędzi w pod polem z wpisywaniem wiadomości do chata).
6. Upewniamy się że w sekcji `my-mcp-server-mint1` mamy zaznaczone narzędzia które chcemy używać.

Po wykonaniu powyższych kroków, nasz klient MCP powinien być poprawnie skonfigurowany i gotowy do użycia w Copilot Chat.