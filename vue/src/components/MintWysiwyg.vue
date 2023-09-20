<template>
    <div class="mint-wysiwyg">
        <textarea :id="uuid"></textarea>
        <div v-if="$slots.footer" class="mint-wysiwyg-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, watch, ref } from 'vue'
import { v4 as uuidv4 } from 'uuid'
import tinymce, { Editor, RawEditorSettings } from 'tinymce'

import 'tinymce/icons/default'
import 'tinymce/themes/silver'
import 'tinymce/skins/ui/oxide/skin.css'
import 'tinymce/skins/ui/oxide/content.min.css'

interface Props {
    modelValue: string
    options?: RawEditorSettings
}

const props = withDefaults(defineProps<Props>(), {
    options: () => ({}),
})

const emit = defineEmits(['update:modelValue'])

const uuid = uuidv4()
const selector = `.mint-wysiwyg textarea#${uuid}`
const tinymceEditor = ref<null | Editor>(null)

onMounted(() => {
    tinymce.init({
        selector,
        menubar: false,
        statusbar: false,
        promotion: false,
        height: 250,
        toolbar: 'fontselect | fontsizeselect | bold italic underline | forecolor backcolor | styleselect | outdent indent',
        setup: (editor) => {
            tinymceEditor.value = editor
            editor.on('init', () => {
                editor.setContent(props.modelValue)
            })
            editor.on('SetContent', () => {
                emit('update:modelValue', editor.getContent())
            })
            editor.on('input', () => {
                emit('update:modelValue', editor.getContent())
            })
            editor.on('change', () => {
                emit('update:modelValue', editor.getContent())
            })
        },
        ...props.options,
    })
})

watch(
    () => props.modelValue,
    (newVal) => {
        if (newVal !== tinymceEditor.value?.getContent()) {
            tinymceEditor.value?.setContent(newVal)
        }
    },
)
</script>

<style lang="scss">
.mint-wysiwyg {
    border: thin solid #0003;
    position: relative;

    .tox.tox-tinymce {
        border: none;
    }

    .mint-wysiwyg-footer {
        background: white;
        border-top: thin solid #0001;
        padding: 8px 16px;
    }
}
</style>
