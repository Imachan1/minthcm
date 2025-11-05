<template>
    <div class="d-flex align-center ga-3">
        <div class="detail-field-assigned-module">
            <label>{{ languages.label('LBL_ASSIGNED_TO_MODULE') }}</label>
            <div class="detail-field-row" v-on:dblclick.prevent="startInlineEdit()">
                <router-link v-if="hasListAccess" :to="urls.parent" class="relate-field">
                    {{ props.data.bean.attributes.parent_type }}
                </router-link>
                <span v-else>
                    {{ props.data.bean.attributes.parent_type }}
                </span>
                <Pencil :defs="props.defs" />
            </div>
        </div>
        <div class="detail-field-assigned-record">
            <label>{{ languages.label('LBL_ASSIGNED_TO_RECORD') }}</label>
            <div class="detail-field-row">
                <router-link v-if="hasViewAccess" :to="urls.record" class="relate-field">
                    {{ props.modelValue }}
                </router-link>
                <span v-else>
                    {{ props.modelValue }}
                </span>
                <Pencil :defs="props.defs" :hidePencil="hidePencil"
                    @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Pencil from '../Pencil.vue'
import { useLanguagesStore } from '@/store/languages'
import { FieldProps } from '../Field.model';
import { useACL } from '@/composables/useACL';

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()
const emit = defineEmits(['inlineEditBtnClicked'])
const urls = computed(() => {
    const recordModule = props.data.bean.attributes.parent_type
    const recordId = props.data.bean.attributes[props.defs.id_name]
    return { record: `/modules/${recordModule}/DetailView/${recordId}`, parent: `/modules/${recordModule}/ESListView` }
})
function startInlineEdit() {
    if (props?.defs?.name && typeof props.defs.name === 'string' && props.defs.name.length > 0) {
        emit('inlineEditBtnClicked', props.defs.name)
    }
}
const hasListAccess = computed<boolean>(() => {
    return useACL().hasAccess(props.data.bean.attributes.parent_type, 'list', true, true)
})
const hasViewAccess = computed<boolean>(() => {
    return useACL().hasAccess(props.data.bean.attributes.parent_type, 'view', true, true)
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}

div {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}

.relate-field {
    text-decoration: none;
    color: rgb(var(--v-theme-secondary));
    cursor: pointer;
    display: block;
    width: fit-content;
}
.detail-field-assigned-module, .detail-field-assigned-record{
    width: 40%;
}
.detail-label-related-to{
    width: 20%;
}
</style>
