# MintHCM 5.x — selektory

> **Uwaga:** Te selektory są orientacyjne — zweryfikuj je na żywej instancji w DevTools przed użyciem w testach.

## Login

```javascript
this.usernameInput = page.locator('input[name="username"]');
this.passwordInput = page.locator('input[name="password"]');
this.loginButton = page.locator('button.mint-button-primary');
this.errorMessage = page.locator('.mint-status-box-error');
```

## EditView (wewnątrz iframe)

```javascript
this.iframeLocator = page.frameLocator('iframe').first();

// Typowe pola
this.lastNameInput = this.iframeLocator.locator('#last_name');
this.firstNameInput = this.iframeLocator.locator('#first_name');
this.emailInput = this.iframeLocator.locator('#email1');
this.phoneInput = this.iframeLocator.locator('#phone_mobile');
this.descriptionInput = this.iframeLocator.locator('#description');

// Przyciski
this.saveButton = this.iframeLocator.locator('#SAVE').first();
this.cancelButton = this.iframeLocator.locator('#CANCEL');

// Walidacja
this.validationMessage = this.iframeLocator.locator('.validation-message');
this.requiredField = this.iframeLocator.locator('.required');
```

## DetailView (wewnątrz iframe)

```javascript
// Wartości pól w DetailView mają pattern: #detail_<field_name>
this.detailLastName = this.iframeLocator.locator('#detail_last_name');
this.detailFirstName = this.iframeLocator.locator('#detail_first_name');
```

## ListView

```javascript
// Lista rekordów
this.listTable = this.iframeLocator.locator('.list.view table');
this.listRows = this.iframeLocator.locator('.list.view table tbody tr');

// Paginacja
this.nextPage = this.iframeLocator.locator('.listViewPagination .next');
this.prevPage = this.iframeLocator.locator('.listViewPagination .prev');
```

## Toast messages (poza iframe)

```javascript
this.successMessage = page.locator('.mint-status-box-success');
this.errorMessage = page.locator('.mint-status-box-error');
```

## Nawigacja (poza iframe)

```javascript
// Menu górne
this.moduleMenu = page.locator('.mint-navigation');
// Global search
this.globalSearch = page.locator('.mint-global-search input');
```
