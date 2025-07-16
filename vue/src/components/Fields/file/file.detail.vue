<template>
  <div>
    <label>{{ props.label }}</label>
    <div class="detail-field-row">
      <a v-if="props.modelValue" class="mint-file-detail-field" :href="`api/files/${props.modelValue}`" target="_blank" rel="noopener noreferrer">
        <span>{{ props.modelValue }}</span>
        <v-icon size="x-small">mdi-file</v-icon>
      </a>
      <span v-else>N/A</span>
      <Pencil :defs="props.defs" @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)" />
    </div>
  </div>
</template>

<script setup lang="ts">
import Pencil from '../Pencil.vue'
import { FieldVardef } from '@/store/modules'

interface Props {
  defs: FieldVardef
  label: string
  modelValue?: any
  data?: any
}
const props = defineProps<Props>()
</script>

<style scoped lang="scss">
label {
  font-size: 12px;
  color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}

.mint-file-detail-field {
  color: rgb(var(--v-theme-secondary));
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 6px;
  overflow-wrap: break-word;
}
</style>