# Naming conventions — Python

Detailed rules and examples for Python. Read this when you need to confirm a specific convention or assess whether existing Python code follows the standards.

---

## General rules

- Variables, parameters, functions, methods: `snake_case` (`my_variable`, `user_id`, `calculate_total()`)
- Instance fields: `snake_case` (`self.tax_rate`, `self.user_name`)
- "Private" fields (convention): `_snake_case` (`self._internal_state`)
- "Very private" fields (name mangling): `__snake_case` (`self.__secret`)
- Classes: `PascalCase` (`InvoiceCalculator`, `UserRepository`)
- Module-level constants: `UPPER_SNAKE_CASE` (`MAX_RETRY_COUNT`, `DEFAULT_TIMEOUT`)
- Files/modules: `snake_case` (`invoice_calculator.py`, `user_service.py`)
- Packages: `lowercase` without underscores where possible (`invoicing`, `users`)
- Interfaces (ABC): `PascalCase` without `I` prefix (`InvoiceCalculator` as ABC)

## PEP 8 compliance

Python has an official style guide — PEP 8. The rules above are consistent with it. When in doubt, treat PEP 8 as the authority.

## Examples

```python
# Good
MAX_TAX_RATE = 0.5

class InvoiceCalculator:
    def __init__(self, tax_rate: float):
        self._tax_rate = tax_rate

    def calculate_total(self, net_amount: float, discount_rate: float) -> float:
        discounted = self._apply_discount(net_amount, discount_rate)
        return discounted * (1 + self._tax_rate)

    def _apply_discount(self, amount: float, rate: float) -> float:
        return amount * (1 - rate)


# Bad
class invoiceCalculator:      # PascalCase required
    def __init__(self, TaxRate):  # PascalCase parameter is wrong
        self.taxRate = TaxRate    # class field should be snake_case

    def CalculateTotal(self, netAmount):  # method should be snake_case
        print(f"debug: {netAmount}")  # remove before committing
        return netAmount * self.taxRate
```
