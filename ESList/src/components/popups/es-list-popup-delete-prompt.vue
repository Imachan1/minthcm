<template>
    <ESListPopup
        :title="label('LBL_DELETE_RECORD_CONFIRM')"
        @close-popup="$emit('close-popup')"
        :style="{ minWidth: '350px' }"
    >
        <span v-text="label('LBL_ARE_YOU_SURE') + ' ' + (name || id)" />
        <v-divider class="mt-4" />
        <div class="es-list-popup-delete-prompt-buttons mt-4">
            <v-btn @click="$emit('close-popup')" outlined color="#009976" v-text="label('LBL_NO')" />
            <v-btn @click="$emit('confirm', id)" dark color="#009976" v-text="label('LBL_YES')" />
        </div>
    </ESListPopup>
</template>

<script>
import { mapGetters } from 'vuex'
import ESListPopup from './es-list-popup'

export default {
    components: { ESListPopup },
    props: {
        id: { type: String, required: true },
        name: { type: String }
    },
    computed: {
        ...mapGetters({
            label: 'getLabel'
        })
    },
}
</script>

<style>
.es-list-popup-delete-prompt-buttons {
    display: flex;
    gap: 16px;
    justify-content: space-between;
}
</style>
