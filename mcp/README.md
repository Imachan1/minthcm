# Mint MCP

Mint MCP is a module in Mint that acts as an MCP server and enables integration with MCP clients such as Visual Studio Code, Copilot Chat, etc. Thanks to this, you can use MCP tools in Mint and add your own.

## Server Configuration

### Adding config file
1. Copy the example configuration file to the `Config` directory:
```bash
cp Config/mcp_conifg.php.example Config/mcp_conifg.php
```
2. Open the `Config/mcp_conifg.php` file and adjust the configuration according to your needs. For example, you can set the `use_whitelist` and `use_blacklist` options to control which modules are available in MCP.

### Generating an Access Token

For authorization, we use standard tokens from the V8 API.

Make sure you have generated keys in the folder:
```bash
cd legacy/Api/V8/OAuth2
```
If you don't have the keys, generate them with:
```bash
openssl genrsa -out private.key 2048;openssl rsa -in private.key -pubout -out public.key;sudo chmod 600 private.key public.key;sudo chown www-data:www-data p*.key
```

#### Adding an OAuth2 Client
2. In Mint, add an OAuth2 client:

`Administrator -> Administration -> OAuth2 Clients and Tokens`

3. Select "New Password Client"
4. Use DevTools to remove `display: none;` from the password field, enter a password (e.g., qwerty123), and save.

#### Generating an Access Token
1. Use curl or Postman to make a POST request to the endpoint:

```bash
POST "https://your-mint-domain/legacy/Api/access_token"

{
  "grant_type": "password",
  "client_id": "e7e62cbb-a03d-31d1-d90f-685929bf19ed", # change to your client_id
  "client_secret": "qwerty123", # client password
  "scope": "",
  "username": "admin",
  "password": "qwerty" # admin account password
}
```

You will receive an access token in the response:

```json
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI.....",
    "refresh_token": "def50200eb5536b90e123adc36ad962e1b0b9341d10bb69d7630f...."
}
```

2. Take the access_token and use it in the Authorization header for requests to MCP.

## MCP Client Configuration

The following example describes configuration for the MCP client in Visual Studio Code; it should be similar for other clients.

1. Open settings in VS Code (command: Open User Settings (JSON))
2. Add a new MCP server to the `mcp` section:
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
3. Run the command `MCP: List Servers` and select your server `my-mcp-server-mint1`.
4. Select `Start server` and wait for the message `Connection state: Running` and then `Discovered X tools`.
5. Open Copilot Chat, set the mode to `Agent`, and select `"Configure tools..."` (the tools icon under the chat input field).
6. Make sure that in the `my-mcp-server-mint1` section, the tools you want to use are checked.

After completing these steps, your MCP client should be properly configured and ready to use in