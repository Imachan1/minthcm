<template>
    <VContainer
      fluid
      class="px-5"
    >
      <VRow>
        <VCol>
            <v-switch
                small
                v-model="filters.assigned_user_id"
                :value="defs.current_user.id"
                class="mr-3 text-caption font-weight-bold"
                :label="getAppString('LBL_ESLIST_MY_OBJECTS')"
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
  name: 'Base-Filters',
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
    filters: {
      type: Object
    }
  },
  computed: {
    getAppString () {
      return label => this.defs.app_strings?.[label] ?? label
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
    font-size: 24px !important;
  }

  .v-select.v-select--chips.v-select--chips--small .v-select__selections {
    min-height: 32px;
  }
  </style>
