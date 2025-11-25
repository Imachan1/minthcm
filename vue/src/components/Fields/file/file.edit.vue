<template>
    <v-file-input
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        :modelValue="file"
        :error="props.state === 'error'"
        @update:modelValue="(v) => updateModelValue(v)"
        @click:clear="() => updateModelValue(null)"
        :prepend-icon="isImage ? 'mdi-image' : 'mdi-paperclip'"
        :accept="[isImage ? 'image/*' : '*']"
    />
</template>

<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const emit = defineEmits<{
    (e: 'update:modelValue', value: File): void
}>()

const file = ref<File>(getEmptyFile())

onMounted(() => {
    if (props.modelValue) {
        file.value = new File([], props.modelValue)
    }
})

async function updateModelValue(value: File | File[] | null) {
    if (!value) {
        file.value = getEmptyFile()
    } else {
        file.value = Array.isArray(value) ? value[0] : value
    }
    emit('update:modelValue', file.value)
}

const isImage = computed(() => {
    return props.defs.type === 'image'
})

function getEmptyFile(): File {
    return new File([], '')
}

watch(
    () => props.modelValue,
    (newVal) => {
        if (newVal !== file.value.name) {
            file.value = newVal ? new File([], newVal) : getEmptyFile()
        }
    },
)
</script>

<style lang="scss" scoped></style>
