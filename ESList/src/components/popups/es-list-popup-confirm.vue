<template>
    <ESListPopup
        :title="title || label('LBL_ESLIST_POPUP_CONFIRM_DEFAULT_TITLE')"
        @close-popup="$emit('close-popup')"
        :style="{ minWidth: '350px' }"
    >
        <span v-text="body" />
        <v-divider class="mt-4" />
        <div class="d-flex justify-space-between mt-4">
            <v-btn @click="$emit('close-popup')" outlined v-text="label('LBL_ESLIST_NO')" />
            <v-btn @click="$emit('confirm')" color="primary" v-text="label('LBL_ESLIST_YES')" />
        </div>
    </ESListPopup>
</template>

<script>
import { mapGetters } from 'vuex'
import ESListPopup from './es-list-popup'

export default {
    components: { ESListPopup },
    props: {
        title: { type: String },
        body: { type: String },
    },
    computed: {
        ...mapGetters({
            label: 'getLabel'
        })
    },
}
</script>

<style>

</style>
