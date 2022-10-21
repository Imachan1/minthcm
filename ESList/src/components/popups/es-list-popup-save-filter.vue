<template>
    <ESListPopup
        :title="label('LBL_ESLIST_SAVE_FILTER')"
        @close-popup="$emit('close-popup')"
        :style="{ minWidth: '350px' }"
    >
        <v-text-field
            ref="input"
            v-model="filterName"
            :label="label('LBL_ESLIST_FILTER_NAME')"
            outlined
            dense
            :error-messages="errorMsg"
        />
        <v-divider class="mt-4" />
        <div class="es-list-popup-save-filter-buttons mt-4">
            <v-btn @click="$emit('close-popup')" outlined v-text="label('LBL_ESLIST_CANCEL')" />
            <v-btn @click="save" color="primary" v-text="label('LBL_ESLIST_SAVE')" />
        </div>
    </ESListPopup>
</template>

<script>
import { mapGetters } from 'vuex'
import ESListPopup from './es-list-popup'

export default {
    components: { ESListPopup },
    props: {
        initialFilterName: { type: String }
    },
    data() {
        return {
            filterName: this.initialFilterName,
            errorMsg: '',
        }
    },
    computed: {
        ...mapGetters({
            label: 'getLabel'
        })
    },
    mounted() {
        this.$refs.input.focus()
    },
    methods: {
        save() {
            if (this.validate()) {
                this.$emit('save-filter', this.filterName.trim())
            }
        },
        validate() {
            this.errorMsg = ''
            let valid = true
            if (!this.filterName?.trim()) {
                this.errorMsg = this.label('LBL_ESLIST_REQUIRED_FIELD_ERROR')
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
    justify-content: space-between;
}
</style>
