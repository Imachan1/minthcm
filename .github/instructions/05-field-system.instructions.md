---
applyTo:
  - "vue/src/**/Fields/**"
  - "modules/**/vardefs.php"
  - "legacy/**/vardefs.php"
  - "legacy/custom/**/Ext/Vardefs/**"
---

# Field System

The Field system dynamically renders form inputs based on field type and view mode.

## Core Concept

[Field.vue](../../vue/src/components/Fields/Field.vue) router loads `{type}.{view}.vue` components:

```
Field type="string" view="edit" → loads string.edit.vue
Field type="enum" view="detail" → loads enum.detail.vue
```

## Field Structure

```
components/Fields/
├── Field.vue           # Router component
├── Field.config.ts     # Type mappings
├── string/
│   ├── string.edit.vue
│   ├── string.detail.vue
│   └── string.list.vue
└── {type}/
    ├── {type}.edit.vue
    ├── {type}.detail.vue
    └── {type}.list.vue
```

## Using Fields

```vue
<Field 
  :defs="{ type: 'string', name: 'email', required: true }"
  :view="'edit'"
  v-model="bean.attributes.value.email"
  :label="'Email'"
  :required="bean.logic.requiredFields.value.has('email')"
  :disabled="bean.logic.readonlyFields.value.has('email')"
  :isDirty="bean.dirtyFields.value.has('email')"
  :errorMessage="bean.errorMessages.value.email"
/>
```

## Props Interface

```typescript
interface FieldProps {
  defs: FieldDefinition      // Field metadata from backend
  view: 'edit' | 'detail' | 'list'
  modelValue: any            // Current value
  label?: string
  required?: boolean
  disabled?: boolean
  isDirty?: boolean
  errorMessage?: string
}
```

## Creating Custom Field Type

See [Customization Guide](03-customization.md#example-2-custom-field-type) for complete example.

**Steps**:
1. Create `custom/components/Fields/{type}/`
2. Add `{type}.edit.vue`, `{type}.detail.vue`, `{type}.list.vue`
3. Emit `update:modelValue` on changes
4. Define in backend vardefs with matching type

**Full Documentation**: `vue/documentation/09-fields.md`

---

**Related**: [Vue Frontend](04-frontend-vue.md), [CRUD Operations](07-crud-operations.md)
