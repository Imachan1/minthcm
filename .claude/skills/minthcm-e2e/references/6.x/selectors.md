# MintHCM 6.x — selektory

> **Uwaga:** MintHCM 6.x jest w rozwoju. Te selektory mogą się zmieniać — zweryfikuj na żywej instancji w DevTools.

## Zmiany vs 5.x

MintHCM 6.x wprowadza zmiany w UI:
- Nowa nawigacja (możliwe zmiany w selektorach menu)
- Potencjalne zmiany w strukturze iframe
- Nowe komponenty Vue

## Login

```javascript
// Może się zmienić w 6.x — zweryfikuj
this.usernameInput = page.locator('input[name="username"]');
this.passwordInput = page.locator('input[name="password"]');
this.loginButton = page.locator('button.mint-button-primary');
this.errorMessage = page.locator('.mint-status-box-error');
```

## EditView

```javascript
// Iframe — sprawdź czy w 6.x nadal jest ten sam pattern
this.iframeLocator = page.frameLocator('iframe').first();

// Typowe pola — pattern ID powinien się zachować
this.lastNameInput = this.iframeLocator.locator('#last_name');
this.firstNameInput = this.iframeLocator.locator('#first_name');
this.saveButton = this.iframeLocator.locator('#SAVE').first();
```

## Toast messages

```javascript
// W 6.x mogą zmienić się klasy CSS — zweryfikuj
this.successMessage = page.locator('.mint-status-box-success');
this.errorMessage = page.locator('.mint-status-box-error');
```

## Znane zmiany w 6.x

> Ta sekcja będzie aktualizowana w miarę stabilizacji wersji 6.x. Na razie traktuj selektory z 5.x jako punkt startowy i weryfikuj na instancji 6.x.
