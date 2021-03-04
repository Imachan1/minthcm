<template>
  <VCard
    :class="{
      'pa-2': true,
      'mb-2': true,
      ...classesFromParent
    }"
    @click="$emit('item-click', item.id)"
  >
    {{item.name}}Base
  </VCard>
</template>

<script>
import { VCard } from 'vuetify/lib'

export default {
  name: 'Base-Item',
  props: {
    item: Object,
    classesFromParent: Object
  },
  components: {
    VCard
  }
}
</script>
