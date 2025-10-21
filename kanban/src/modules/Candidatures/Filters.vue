<template>
    <VContainer
      fluid
      class="px-5"
    >
      <VRow>
        <VCol>
            <VSelect
                v-model="filters.recruitment_id"
                multiple
                chips
                deletable-chips
                small-chips
                hide-details
                attach
                class="ma-0 pa-0"
                :no-data-text="''"
                :label="getModString('LBL_CANDIDATURES_RECRUITMENTS_END_FROM_CANDIDATURES_TITLE')"
                :items="getValuesRecruitmentsFromItems('id', 'name', {})"
                item-text="name"
                item-value="id"
            />
        </VCol>
        <VCol>
            <VSelect
                v-model="filters.assigned_user_id"
                multiple
                chips
                deletable-chips
                small-chips
                hide-details
                attach
                class="ma-0 pa-0"
                :no-data-text="''"
                :label="getModString('LBL_ASSIGNED_TO_NAME')"
                :items="getValuesFromItems('assigned_user_id', 'assigned_user_name', {'': getAppString('LBL_KANBAN_UNASSIGNED')})"
                item-text="name"
                item-value="id"
            />
        </VCol>
        <VCol class="d-flex align-center justify-start">
            <VBtn
                small
                color="#F05A41"
                class="mr-3 text-caption font-weight-bold clear"
                text
                outlined
                v-text="getAppString('LBL_CLEAR_BUTTON_KANBAN_LABEL')"
                @click="$emit('clearFilters')"
            />
        </VCol>
      </VRow>
    </VContainer>
  </template>

<script>
import { VContainer, VRow, VCol, VBtn } from 'vuetify/lib'

export default {
  name: 'Candidatures-Filters',
  components: {
    VContainer,
    VRow,
    VCol,
    VBtn
  },
  props: {
    defs: {
      type: Object
    },
    items: {
      type: Object
    },
    assignedUsers: {
      type: Object
    },
    filters: {
      type: Object
    }
  },
  watch: {
    items (newItems) {
      if (newItems) {
        this.items = newItems
      }
    }
  },
  computed: {
    getAppString () {
      return label => this.defs.app_strings?.[label] ?? label
    },
    getModString () {
      return label => this.defs.mod_strings?.[label] ?? label
    },
    getValuesFromItems () {
      return function (key, value, additionalValues = {}) {
        const values = additionalValues
        Object.values(this.items).flat().forEach(function (item) {
          if (item[key] && item[value] && !values[item[key]]) {
            values[item[key]] = item[value]
          }
        })
        return Object.entries(values).map(([id, name]) => ({ id: id, name: name }))
      }
    },
    getValuesRecruitmentsFromItems () {
      return function (key, value, additionalValues = {}) {
        const values = additionalValues
        if(this.items.recruitments){
          Object.values(this.items.recruitments).flat().forEach(function (item) {
            if (item[key] && item[value] && !values[item[key]]) {
              values[item[key]] = item[value]
            }
          })
        }
        return Object.entries(values).map(([id, name]) => ({ id: id, name: name }))
      }
    }
  }
}
</script>

  <style scoped>
  button.clear {
    background-color: #00654e;
    color: white !important;
    font-size: 1.25rem !important;
    height: 40px !important;
  }
  </style>

  <style>

  .v-list-item__title {
    font-size: 12px !important;
  }
  .v-menu__content {
    max-height: 300px;
    overflow-y: auto;
    top: 10px !important;
  }
  .v-select.v-select--chips.v-select--chips--small .v-select__selections {
    min-height: 32px;
  }
  </style>
