# Naming conventions — C#

Detailed rules and examples for C#. Read this when you need to confirm a specific convention or assess whether existing C# code follows the standards.

---

## General rules

- Local variables and parameters: `camelCase` (`myVariable`, `userId`)
- Private fields: `_camelCase` with underscore prefix (`_taxRate`, `_userRepository`)
- Public properties: `PascalCase` (`TaxRate`, `UserName`)
- Methods: `PascalCase` (`CalculateTotal()`, `GetUserById()`)
- Classes: `PascalCase` (`InvoiceCalculator`, `UserService`)
- Interfaces: `IPascalCase` — `I` prefix is mandatory in C# (`IUserRepository`, `IInvoiceService`)
- Constants and `readonly static`: `PascalCase` (`MaxRetryCount`, `DefaultTimeout`)
- Files: `MyClass.cs` = one file, one class
- Namespaces: `PascalCase` separated by dots (`Project.Crm.Invoicing`)
- Public fields (rarely used, prefer properties): `PascalCase`
- Enums and their values: `PascalCase` (`InvoiceStatus.Paid`, `UserRole.Admin`)
- Events: `PascalCase` (`OnInvoiceCreated`, `UserLoggedIn`)

## Examples

```csharp
// Good
public interface IInvoiceCalculator
{
    decimal CalculateTotal(decimal netAmount, decimal discountRate);
}

public class InvoiceCalculator : IInvoiceCalculator
{
    private readonly decimal _taxRate;

    public InvoiceCalculator(decimal taxRate)
    {
        _taxRate = taxRate;
    }

    public decimal CalculateTotal(decimal netAmount, decimal discountRate)
    {
        var discounted = ApplyDiscount(netAmount, discountRate);
        return discounted * (1 + _taxRate);
    }

    private decimal ApplyDiscount(decimal amount, decimal rate)
    {
        return amount * (1 - rate);
    }
}

// Bad
public class invoiceCalculator  // class in lowercase
{
    public decimal taxRate;     // public field in camelCase

    public decimal calculate_total(decimal NetAmount)  // snake_case method, PascalCase parameter
    {
        Debug.WriteLine("debug: " + NetAmount);  // remove before committing
        return NetAmount * taxRate;
    }
}
```
