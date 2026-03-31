# Naming conventions — JavaScript / TypeScript

Detailed rules and examples for JavaScript and TypeScript. Read this when you need to confirm a specific convention or assess whether existing JS/TS code follows the standards.

---

## General rules

- Variables and parameters: `camelCase` (`myVariable`, `userId`)
- Class fields: `camelCase` (`myField`, `userName`)
- Private fields (ES2022+): `#myField` or convention `_myField`
- Functions and methods: `camelCase` (`myFunction()`, `getUserById()`)
- Classes: `PascalCase` (`MyClass`, `UserService`)
- Interfaces (TypeScript): `PascalCase` without `I` prefix → `UserRepository`, not `IUserRepository`
- Constants: `UPPER_SNAKE_CASE` for global/module-level values (`MAX_RETRIES`), `camelCase` for local `const`
- Files: context-dependent — React/Vue components: `MyComponent.tsx`/`MyComponent.vue`; JS modules: `myModule.js` or `my-module.js`

## Examples

```js
// Good
const MAX_RETRIES = 3;

class InvoiceCalculator {
  #taxRate;

  constructor(taxRate) {
    this.#taxRate = taxRate;
  }

  calculateTotal(netAmount, discountRate) {
    const discounted = this.#applyDiscount(netAmount, discountRate);
    return discounted * (1 + this.#taxRate);
  }

  #applyDiscount(amount, rate) {
    return amount * (1 - rate);
  }
}

// Bad
class invoiceCalculator {         // class must be PascalCase
  TaxRate = 0.23;                 // public field in PascalCase looks like a class

  Calculate_Total(NetAmount) {    // mixed conventions
    var x = NetAmount * this.TaxRate;
    console.log(x);               // debug log — remove before committing
    return x;
  }
}
```
