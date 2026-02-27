---
name: new-field
description: "Use this skill when the user wants to create a new custom field type in MintHCM. Triggers on: 'new field type', 'custom field', 'nowy typ pola', 'create field component'. Do NOT use for modifying existing field types — only for creating new ones."
---

# Create Custom Field Type in MintHCM

Create a new custom field type with all required Vue components and optional backend vardef definition.

## Input

$ARGUMENTS

Parse the input for:
- **Field type name** (required) — e.g., `rating`, `color-picker`, `signature`
- **Module name** (optional) — if provided, also create the vardef definition
- **DB type** (optional) — e.g., `int`, `varchar(255)`. Default: `varchar(255)`

If field type name is missing, ask the user before proceeding.

## Rules

- **NEVER** create files in `vue/src/components/Fields/` — always use `vue/src/custom/components/Fields/`
- All component names must follow pattern: `{type}.{view}.vue`
- Always implement all three views: `detail`, `edit`, `list`
- Always import `FieldProps` from `@/components/Fields/Field.model`
- Always emit `update:modelValue` in edit component
- Use Vuetify components (`v-*`) for UI
- Use scoped CSS
- Never hardcode labels — use props

## Step 1: Create Component Files

Create the directory and three Vue components:

**`vue/src/custom/components/Fields/{type}/{type}.detail.vue`**
```vue
<template>
  <div class="{type}-detail">
    <!-- Read-only display of modelValue -->
  </div>
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'
defineProps<Pick<FieldProps, 'modelValue' | 'defs' | 'label'>>()
</script>

<style scoped>
.{type}-detail {
  /* detail styles */
}
</style>
```

**`vue/src/custom/components/Fields/{type}/{type}.edit.vue`**
```vue
<template>
  <div class="{type}-edit">
    <!-- Interactive input component -->
    <!-- Show errorMessage if provided -->
  </div>
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'
defineProps<FieldProps>()
defineEmits(['update:modelValue'])
</script>

<style scoped>
.{type}-edit {
  /* edit styles */
}
</style>
```

**`vue/src/custom/components/Fields/{type}/{type}.list.vue`**
```vue
<template>
  <div class="{type}-list">
    <!-- Compact, truncated display for tables -->
  </div>
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'
defineProps<Pick<FieldProps, 'modelValue' | 'defs'>>()
</script>

<style scoped>
.{type}-list {
  /* compact list styles */
}
</style>
```

## Step 2: Check Field.config.ts

Read `vue/src/components/Fields/Field.config.ts` to understand the existing type registration system.

If the project has a custom config at `vue/src/custom/components/Fields/Field.config.ts`, add the new type there. Otherwise check how existing custom types are registered (look for existing custom fields in `vue/src/custom/components/Fields/`).

If type mapping is needed (e.g., backend uses `stars` but component is `rating`):
```typescript
// In the appropriate Field.config.ts
typeMap: {
  'backend_type_name': '{type}',
}
```

## Step 3: Vardef Definition (if module provided)

If a module name was given, create or update the vardef entry:

**`modules/{Module}/vardefs.php`** or **`legacy/modules/{Module}/vardefs.php`**:
```php
$dictionary['{Module}']['fields']['{field_name}'] = [
    'name' => '{field_name}',
    'type' => '{type}',  // Must match component folder name
    'label' => 'LBL_{FIELD_NAME_UPPER}',
    'dbType' => '{db_type}',  // int, varchar, text, etc.
];
```

After editing vardefs, remind the user:
> Run **Admin → Repair → Quick Repair and Rebuild** to regenerate entities.

## Step 4: Summary

After creating files, show:
- List of created files with links
- How to use the field in a Vue template:

```vue
<Field
  :defs="{ type: '{type}', name: 'field_name' }"
  :view="'edit'"
  v-model="bean.attributes.value.field_name"
  :label="languages.label('LBL_FIELD_NAME')"
  :required="bean.logic.requiredFields.value.has('field_name')"
  :disabled="bean.logic.readonlyFields.value.has('field_name')"
  :isDirty="bean.dirtyFields.value.has('field_name')"
  :errorMessage="bean.errorMessages.value.field_name"
/>
```

## FieldProps Interface Reference

```typescript
interface FieldProps {
  defs: FieldDefinition      // Field metadata from backend
  view: 'edit' | 'detail' | 'list'
  modelValue?: any
  label?: string
  required?: boolean
  disabled?: boolean
  isDirty?: boolean
  errorMessage?: string
  state?: 'normal' | 'error' | 'required'
  options?: any
  data?: any                 // Full record (for computed fields)
  hidePencil?: boolean
}
```
