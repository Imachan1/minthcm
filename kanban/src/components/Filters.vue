<template>
  <keep-alive>
    <component
      v-if="filtersComponent"
      :is="filtersComponent"
      :defs="defs"
      :items="items"
      :filters="filters"
      @clearFilters="$emit('clearFilters')"
    />
  </keep-alive>
</template>

<script>
import Vue from 'vue'

export default {
  name: 'Filters',
  props: {
    module: String,
    defs: {
      type: Object,
      default: () => ({})
    },
    items: {
      type: Object,
      default: () => ({})
    },
    filters: {
      type: Object,
      default: () => ({})
    }
  },
  data () {
    return {
      filtersComponent: null
    }
  },
  watch: {
    module (newVal) {
      if (newVal) {
        this.filtersComponent = this.loadFiltersComponent(newVal)
      }
    }
  },
  methods: {
    loadFiltersComponent (module = 'Base') {
      let componentName = module + '-Filters'
      if (!(componentName in Vue.options.components)) {
        try {
          const componentConfig = require('@/modules/' + module + '/Filters')
          Vue.component(componentName, componentConfig.default || componentConfig)
        } catch (e) {
          try {
            const baseComponentConfig = require('@/modules/Base/Filters')
            componentName = 'Base-Filters'

            if (!(componentName in Vue.options.components)) {
              Vue.component(componentName, baseComponentConfig.default || baseComponentConfig)
            }
          } catch (e2) {
            componentName = false
          }
        }
      }
      return componentName
    }
  }
}
</script>
