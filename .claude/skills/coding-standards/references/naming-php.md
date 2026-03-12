# Naming conventions — PHP

Detailed rules and examples for PHP. Read this when you need to confirm a specific convention or assess whether existing PHP code follows the standards.

---

## General rules

- Local variables and parameters: `snake_case` (`$my_variable`, `$user_id`)
- Class fields: `snake_case` (`$my_field`)
- Methods and functions: `camelCase` (`myFunction()`, `getUserById()`)
- Classes and interfaces: `PascalCase` (`MyClass`, `UserRepository`, `MyInterface`)
- Constants: `UPPER_SNAKE_CASE` (`MAX_RETRY_COUNT`, `DEFAULT_TIMEOUT`)
- Files: filename = class/interface name (`MyClass.php`, `UserRepository.php`)
- Function parameters: `snake_case` with `$` prefix → `myFunction($param_one, $param_two)`
- Use BeanFactory, ViewFactory, ControllerFactory instead of direct `new`
- One file = one class; names must be strictly consistent

## Examples

```php
// Good
class InvoiceCalculator
{
    private $tax_rate;

    public function calculateTotal($net_amount, $discount_rate): float
    {
        $discounted = $this->applyDiscount($net_amount, $discount_rate);
        return $discounted * (1 + $this->tax_rate);
    }

    private function applyDiscount($amount, $rate): float
    {
        return $amount * (1 - $rate);
    }
}

// Bad — don't do this
class calc  // PascalCase required
{
    private $taxRate;  // camelCase fields are wrong

    public function CalcTotal($netAmount, $discountRate)  // PascalCase method is wrong
    {
        // ...
    }
}
```
