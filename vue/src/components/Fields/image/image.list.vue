<template>
  <div class="mint-file-list-field" v-if="fileName">
    <v-icon size="x-small">mdi-file</v-icon>
    <span>{{ fileName }}</span>
  </div>
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'
// CR: to będzie do napisania prawie od zera - obecnie wyświetla nazwę pliku

interface Props {
  defs: FieldVardef
  data?: any
}

const props = defineProps<Props>()
const fileName = props.data?.bean?.[props.defs.name]
</script>

<style scoped lang="scss">
.mint-file-list-field {
  color: rgb(var(--v-theme-on-surface));
  display: flex;
  align-items: center;
  gap: 6px;
  overflow-wrap: break-word;
}
</style>