<template>
  <VApp data-app="true">
    <Filters
      :module="module"
      :defs="defs"
      :items="items"
      :filters="filters"
      @clearFilters="filters = {}"
    />
    <Table
      :defs="defs"
      :items="filteredItems"
      @item-change="
        (itemID, oldOrder, newOrder, oldStatus, newStatus) =>
          $emit('item-change', itemID, oldOrder, newOrder, oldStatus, newStatus)
      "
      @item-add="(key) => $emit('item-add', key)"
      @item-click="(itemID) => $emit('item-click', itemID)"
      @open-fullscreen="(itemID) => $emit('open-fullscreen', itemID)"
    />
  </VApp>
</template>

<script>
import { VApp } from 'vuetify/lib'
import vuetify from './plugins/vuetify'
import Table from './components/Table'
import Filters from './components/Filters'
export default {
  name: 'Kanban',
  vuetify,
  components: {
    VApp,
    Table,
    Filters
  },
  computed: {
    filteredItems () {
      const filteredItems = {}
      Object.keys(this.items).forEach((category) => {
        filteredItems[category] = this.items[category].filter((item) =>
          Object.keys(this.filters).every(
            (key) =>
              !(
                this.filters[key] &&
                this.filters[key].length &&
                !this.filters[key].includes(item[key])
              )
          )
        )
      })
      return filteredItems
    }
  },
  data () {
    return {
      filters: {},
      module: '',
      defs: {},
      items: {}
    }
  }
}
</script>

<style>
@import "../node_modules/vuetify/dist/vuetify.min.css";
@import url("https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css");
.v-application {
  background: inherit !important;
}
</style>
