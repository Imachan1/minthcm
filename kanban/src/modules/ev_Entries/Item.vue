<template>
  <VCard
    v-ripple="item.editable || item.detailview"
    :class="{
      'pa-2': true,
      'mb-2': true,
      'high-priority': item.has_something_changed,
      'inactive': !item.editable && !item.detailview,
      pointer: item.editable || item.detailview,
      default: !item.editable && !item.detailview,
      ...classesFromParent
    }"
    @click="$emit('item-click', item)"
  >
    <p class="ma-0 mb-4 font-weight-bold">{{item.accommodation_name}}</p>
    <p class="ma-0 mb-2">
      <VIcon>mdi-text-subject</VIcon> <span v-html="item.entry_subject" />
    </p>
    <p
      v-if="item.planned_realization_date"
      :class="{
        'ma-0': true,
        'mb-2': true,
        'expired': expired
      }"
    >
      <VIcon>mdi-calendar</VIcon> {{item.planned_realization_date | toUserDateTime(this.defs.user_date_format, this.defs.user_time_format)}}
    </p>
    <p class="icons ma-0 mt-2 text-right">
      <VIcon
        v-if="item.entry_priority === 'high' || item.returning_entry == 1"
        class="px-1 alert"
        small
      >
        mdi-alert-circle
      </VIcon>
      <VIcon
        v-if="item.has_shopping"
        class="px-1 inactive"
        small
        @click.stop="$emit('item-cart-click', item.id)"
      >
        mdi-cart
      </VIcon>
    </p>
  </VCard>
</template>

<script>
import { VCard, VIcon } from 'vuetify/lib'
import moment from 'moment'
require('moment/locale/pl')
moment.locale('pl')

export default {
  name: 'ev_Entries-Item',
  props: {
    item: Object,
    defs: Object,
    classesFromParent: Object
  },
  components: {
    VCard,
    VIcon
  },
  computed: {
    expired () {
      return (!this.defs.expired_excluded_columns || this.defs.expired_excluded_columns.indexOf(this.item.entry_status) === -1) && moment.utc().isAfter(moment.utc(this.item.planned_realization_date))
    }
  },
  filters: {
    toUserDateTime (value, dateFormat, timeFormat) {
      return (dateFormat && timeFormat) ? moment.utc(value).local().format(dateFormat + ' ' + timeFormat) : value
    }
  }
}
</script>

<style scoped>
.v-card.high-priority {
  border-left: 3px solid #f05a41;
}
.v-icon {
  color: #3750a0;
}
.v-icon.inactive {
  color: rgba(0, 0, 0, 0.54);
}
p.expired,
p.expired .v-icon,
.v-icon.alert {
  color: #f05a41;
}
.v-card.pointer {
  cursor: pointer;
}
.v-card.default {
  cursor: default;
}
</style>
