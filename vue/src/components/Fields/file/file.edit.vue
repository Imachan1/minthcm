<template>
  <div class="file-input-container">
    <v-file-input
      class="file-input"
      :label="props.label"
      variant="outlined"
      density="compact"
      hide-details
      show-size
      prepend-icon=""
      :error="props.state === 'error'"
      @change="handleFileUpload"
      @keyup.enter="emit('inlineEditSave')"
      @keyup.esc="emit('inlineEditCancel')"
    />
    <v-icon class="file-icon">mdi-paperclip</v-icon>
  </div>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
  (e: 'inlineEditSave'): void
  (e: 'inlineEditCancel'): void
}>()

function handleFileUpload(event: Event) {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0]
  if (file) {
    emit('update:modelValue', file.name)
  }
}
</script>

<style scoped lang="scss">
.file-input-container {
  display: flex;
  align-items: center;
  gap: 8px;
  max-width: 100%;
}

.file-input {
  flex-grow: 1;
}

.file-icon {
  color: rgba(0, 0, 0, 0.54);
  flex-shrink: 0;
}
</style>
