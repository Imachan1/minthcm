<template>
  <div>
    <v-file-input
      :label="props.label"
      variant="outlined"
      density="compact"
      hide-details
      show-size
      :error="props.state === 'error'"
      @change="handleFileUpload"
      @keyup.enter="emit('inlineEditSave')"
      @keyup.esc="emit('inlineEditCancel')"
    />

    <div v-if="fileUrl" class="file-preview">
      <img v-if="isImage" :src="fileUrl" alt="preview" class="preview-img" />
      
      <iframe
        v-else
        :src="fileUrl"
        class="preview-iframe"
        frameborder="0"
      ></iframe>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
  (e: 'inlineEditSave'): void
  (e: 'inlineEditCancel'): void
}>()

const fileUrl = ref<string | null>(null)
const isImage = ref<boolean>(false)

function handleFileUpload(event: Event) {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0]
  if (file) {
    const url = URL.createObjectURL(file)
    fileUrl.value = url
    isImage.value = file.type.startsWith('image/')
    emit('update:modelValue', file) 
  }
}
</script>

<style scoped lang="scss">
.file-preview {
  margin-top: 1rem;
}
.preview-img {
  max-width: 300px;
  max-height: 300px;
  border: 1px solid #ccc;
}
.preview-iframe {
  width: 100%;
  height: 400px;
  border: 1px solid #ccc;
}
</style>
