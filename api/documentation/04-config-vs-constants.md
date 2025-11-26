# Configuration vs Constants

Understanding the difference between configuration and constants is important for proper application customization.

## Quick Reference

| Aspect | Constants | Configuration |
|--------|-----------|---------------|
| **Location** | `constants/` | `configs/` |
| **Purpose** | Application-wide definitions | Runtime settings |
| **Extensible** | ✅ Yes (via `custom/constants/`) | ❌ No |
| **Examples** | Module icons, menu items, enums | OAuth keys, database settings |
| **File Type** | PHP arrays | Various (PHP, JSON, keys) |
| **When to Use** | UI definitions, app behavior | Secrets, environment-specific |

## Constants

### What Are Constants?

Constants are **application-wide definitions** that define behavior, appearance, and structure. They are:

- Defined in PHP files that return arrays
- Loaded via `ConstantsLoader` class
- **Extensible** via the `custom/constants/` directory
- Version-controlled (committed to git)

### Location

```
api/constants/
├── colored_enum.php       # Color codes for enum values
├── legacy_views.php       # Legacy view type mappings
├── list_constants.php     # List view configuration
├── menu_icons.php         # Menu icon mappings
├── module_icons.php       # Module icon definitions
└── quick_create.php       # Quick create configurations
```

### Example: Module Icons

**File:** `constants/module_icons.php`

```php
<?php
return [
    'Employees' => 'user',
    'Accounts' => 'building',
    'Contacts' => 'users',
    'Leads' => 'user-plus',
];
```

### How Constants Are Loaded

The `ConstantsLoader` class loads constants and automatically merges custom overrides:

```php
// utils/ConstantsLoader.php
class ConstantsLoader
{
    private const NORMAL_PATH = 'constants/';
    private const CUSTOM_PATH = 'custom/constants/';

    public static function getConstants(string $name): array | false
    {
        $file_path = self::NORMAL_PATH . $name . '.php';
        $custom_files_path = self::CUSTOM_PATH . $name . '/';

        // Load base constants
        if (file_exists($file_path)) {
            $include_content = include $file_path;
            
            // Load custom extensions
            $custom_files = scandir($custom_files_path);
            if (!empty($custom_files)) {
                foreach ($custom_files as $custom_file) {
                    if (substr($custom_file, -4) !== '.php') {
                        continue;
                    }
                    
                    $custom_content = include $custom_files_path . $custom_file;
                    // Merge custom constants with base
                    $include_content = array_merge($include_content, $custom_content);
                }
            }
        } else {
            return false;
        }

        return $include_content;
    }
}
```

### Using Constants in Code

```php
// Load constants
$moduleIcons = ConstantsLoader::getConstants('module_icons');

// Use them
$icon = $moduleIcons['Employees'] ?? 'default';
```

## Extending Constants

To add or override constants, create files in the `custom/constants/{constant_name}/` directory:

### Example: Adding Custom Module Icons

**Step 1:** Create the custom constants directory

```bash
mkdir -p custom/constants/module_icons
```

**Step 2:** Create a custom file

**File:** `custom/constants/module_icons/my_custom_icons.php`

```php
<?php
return [
    'MyCustomModule' => 'star',
    'Employees' => 'user-tie',  // Override existing icon
];
```

**Result:** When `ConstantsLoader::getConstants('module_icons')` is called:
1. Base constants loaded from `constants/module_icons.php`
2. Custom constants loaded from `custom/constants/module_icons/my_custom_icons.php`
3. Arrays merged (custom values override base values)

### Multiple Custom Files

You can have multiple custom constant files:

```
custom/constants/module_icons/
├── 01-company-icons.php
├── 02-hr-icons.php
└── 99-overrides.php
```

All files are merged in alphabetical order. Use numeric prefixes to control merge order.

## Configuration

### What Is Configuration?

Configuration files contain **runtime settings** such as:

- Database credentials
- OAuth2 keys
- API secrets
- Environment-specific settings

These are **NOT extensible** via the `custom/` directory and should **NOT** be committed to version control (use `.gitignore`).

### Location

```
api/configs/
├── .gitignore
├── private.key            # OAuth2 private key (RSA)
├── public.key             # OAuth2 public key (RSA)
└── mint/                  # MintHCM-specific configs
```

### AppConfig Class

The `AppConfig` class provides application-wide configuration values:

```php
// app/Config/AppConfig.php
namespace MintHCM\Api\Config;

class AppConfig
{
    public static function getBasePath()
    {
        $appBasePath = "/api";
        $currentDirectoryName = dirname($_SERVER['PHP_SELF']);
        
        if (str_contains($currentDirectoryName, '/api') 
            && $appBasePath != $currentDirectoryName) {
            $appBasePath = $currentDirectoryName;
        }
        
        return $appBasePath;
    }
}
```

### Using Configuration

```php
use MintHCM\Api\Config\AppConfig;

$basePath = AppConfig::getBasePath();
```

### Database Configuration

Database configuration is inherited from the legacy MintHCM installation. Doctrine reads connection settings from:

```
../legacy/config.php
```

### OAuth2 Keys

OAuth2 keys are stored in `configs/`:

```
configs/
├── private.key    # Generated RSA private key
└── public.key     # Generated RSA public key
```

**Generate keys:**

```bash
cd configs
# Generate private key
openssl genrsa -out private.key 2048
# Generate public key
openssl rsa -in private.key -pubout -out public.key
```

## When to Use Constants vs Configuration

### Use Constants When:

- ✅ Defining UI elements (icons, colors, labels)
- ✅ Application behavior that should be version-controlled
- ✅ Values that need to be extended by customizations
- ✅ Enumerations, mappings, list definitions
- ✅ Module-specific default settings

**Examples:**
- Module icon mappings
- Enum color codes
- Quick create field lists
- Default view configurations

### Use Configuration When:

- ✅ Storing secrets (passwords, API keys, OAuth tokens)
- ✅ Environment-specific settings (dev, staging, prod)
- ✅ Database connection strings
- ✅ Third-party service credentials
- ✅ File paths that vary by environment

**Examples:**
- OAuth2 private/public keys
- Database credentials
- SMTP server settings
- External API endpoints

## Best Practices

### Constants

1. **Keep constants organized by purpose**
   ```
   constants/
   ├── module_icons.php      # All module icons
   ├── colored_enum.php      # All enum colors
   └── quick_create.php      # All quick create configs
   ```

2. **Use descriptive keys**
   ```php
   // ✅ Good
   'Employees' => 'user-tie'
   
   // ❌ Bad
   'emp' => 'ut'
   ```

3. **Document complex constants**
   ```php
   <?php
   return [
       // Icon for employee module (FontAwesome name)
       'Employees' => 'user-tie',
   ];
   ```

4. **Extend, don't modify**
   ```bash
   # ✅ Good - create custom file
   custom/constants/module_icons/my_icons.php
   
   # ❌ Bad - modify core file
   constants/module_icons.php
   ```

### Configuration

1. **Never commit secrets to git**
   ```gitignore
   # configs/.gitignore
   private.key
   public.key
   *.local.php
   ```

2. **Provide example files**
   ```bash
   configs/database.example.php  # Committed
   configs/database.php          # Ignored, created from example
   ```

3. **Use environment variables for sensitive data**
   ```php
   $dbPassword = getenv('DB_PASSWORD') ?: 'default';
   ```

4. **Document configuration requirements**
   ```php
   /**
    * OAuth2 Configuration
    * 
    * Requires:
    * - configs/private.key (RSA 2048-bit)
    * - configs/public.key (generated from private key)
    */
   ```

## Common Patterns

### Pattern: Feature Flags via Constants

```php
// constants/features.php
return [
    'enable_social_feed' => true,
    'enable_chat' => false,
    'enable_notifications' => true,
];

// custom/constants/features/production.php
return [
    'enable_chat' => true,  // Enable in production
];
```

### Pattern: Environment-Specific Config

```php
// app/Config/AppConfig.php
public static function getEnvironment()
{
    return getenv('APP_ENV') ?: 'production';
}

public static function isDebug()
{
    return self::getEnvironment() === 'development';
}
```

### Pattern: Module-Specific Constants

```php
// constants/modules/employees.php
return [
    'default_status' => 'Active',
    'required_fields' => ['first_name', 'last_name', 'email'],
    'max_upload_size' => 5242880, // 5MB
];
```

## Troubleshooting

### Constants Not Loading

**Problem:** Custom constants not appearing

**Solution:**
```bash
# Check directory structure
ls -la custom/constants/module_icons/

# Verify file returns array
php -r "var_dump(include 'custom/constants/module_icons/my_icons.php');"

# Check file naming (must end in .php)
mv my_icons.txt my_icons.php
```

### Configuration Not Found

**Problem:** Config files not loading

**Solution:**
```bash
# Check file exists
ls -la configs/private.key

# Verify permissions
chmod 600 configs/private.key

# Check paths in code
var_dump(realpath('configs/private.key'));
```

## Next Steps

- Learn about [Routing System](./05-routing.md)
- Understand [Extending the API](./08-extending-api.md)
- Explore [Database Communication](./06-database.md)
