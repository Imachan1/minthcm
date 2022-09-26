<template>
    <ESListPopup
        :title="label('LBL_SAVE_FILTER')"
        @close-popup="$emit('close-popup')"
        :style="{ minWidth: '350px' }"
    >
        <v-text-field
            v-model="filterName"
            :label="label('LBL_FILTER_NAME')"
            outlined
            dense
            :error-messages="errorMsg"
        />
        <v-divider class="mt-4" />
        <div class="es-list-popup-save-filter-buttons mt-4">
            <v-btn @click="$emit('close-popup')" outlined color="#009976" v-text="label('LBL_CANCEL')" />
            <v-btn @click="save" dark color="#009976" v-text="label('LBL_SAVE')" />
        </div>
    </ESListPopup>
</template>

<script>
import { mapGetters } from 'vuex'
import ESListPopup from './es-list-popup'

export default {
    components: { ESListPopup },
    data: () => ({
        filterName: '',
        errorMsg: '',
    }),
    computed: {
        ...mapGetters({
            label: 'getLabel'
        })
    },
    methods: {
        save() {
            if (this.validate()) {
                console.log('save filter', this.filterName.trim())
            }
        },
        validate() {
            this.errorMsg = ''
            let valid = true
            if (!this.filterName?.trim()) {
                this.errorMsg = this.label('LBL_REQUIRED_FIELD_ERROR')
                valid = false
            }
            return valid
        }
    }
}
</script>

<style>
.es-list-popup-save-filter-buttons {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
}
</style>
