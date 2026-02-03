---
applyTo:
  - "vue/src/composables/**"
  - "vue/src/views/**"
  - "vue/src/custom/**"
---

# CRUD Operations (useBean)

`useBean` is the core composable for all record operations.

## Basic Usage

```typescript
import { useBean } from '@/composables/useBean'

// Load existing record
const bean = useBean('Employees', '123')
await bean.init()

// Update fields
bean.updateFields({
  first_name: 'John',
  last_name: 'Doe'
})

// Save
if (bean.isValid.value) {
  await bean.save()
}
```

## Key Properties

- `bean.attributes.value` - Current field values
- `bean.syncAttributes.value` - Last saved values (read-only)
- `bean.dirtyFields.value` - Modified fields
- `bean.isValid.value` - Passes validation
- `bean.isChanged.value` - Has unsaved changes
- `bean.errorMessages.value` - Validation errors
- `bean.logic` - MintLogic rules (hiddenFields, requiredFields, readonlyFields)

## Complete CRUD Examples

### Create Record
```typescript
const bean = useBean('Employees')
bean.updateFields({
  first_name: 'Jane',
  last_name: 'Smith',
  email: 'jane@example.com'
})

if (bean.isValid.value) {
  await bean.save()
  console.log(bean.id.value) // New record ID
}
```

### Read Record
```typescript
const bean = useBean('Employees', '456')
await bean.init()
console.log(bean.attributes.value.first_name)
```

### Update Record
```typescript
const bean = useBean('Employees', '456')
await bean.init()

bean.updateFields({ first_name: 'John' })

if (bean.isValid.value && bean.isChanged.value) {
  await bean.save()
}
```

### Delete Record
```typescript
const bean = useBean('Employees', '456')
await bean.init()
await bean.delete()
```

## Critical Rules

**Always**:
- Call `bean.init()` before using
- Use `bean.updateFields()` to modify
- Check `bean.isValid` before saving
- Respect `bean.logic` for dynamic behavior

**Never**:
- Modify `bean.syncAttributes` (read-only)
- Update `bean.attributes.value` directly
- Skip validation

## Working with Logic

```typescript
// Check if field is visible
const isVisible = !bean.logic.hiddenFields.value.has('salary')

// Check if field is required
const isRequired = bean.logic.requiredFields.value.has('email')

// Check if field is readonly
const isReadonly = bean.logic.readonlyFields.value.has('employee_id')
```

See main [copilot-instructions.md](../copilot-instructions.md#usebean-composable-frontend-crud) for complete examples.

**Full Documentation**: `vue/documentation/10-working-with-beans.md`

---

**Related**: [Vue Frontend](04-frontend-vue.md), [MintLogic](12-mintlogic.md), [Field System](05-field-system.md)
