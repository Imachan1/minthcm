<template>
    <div :style="style" ref="popup" class="es-list-popup">
        <div @mousedown="dragStart" ref="draggable" class="es-list-popup-header">
            <span class="es-list-popup-title" v-text="title" />
            <v-btn @click="$emit('close-popup')" icon class="ms-auto">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </div>
        <div class="es-list-popup-content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import draggable from '../../mixins/draggable'

export default {
    mixins: [draggable],
    props: {
        title: { type: String },
        style: { type: Object }
    },
    mounted () {
        this.draggableElement = this.$refs.draggable
        this.draggedElement = this.$el
        this.$root.$el.appendChild(this.draggedElement)
        this.$nextTick(function () {
            this.draggedElement.style.left = (window.innerWidth - this.$el.clientWidth) / 2 + 'px'
            const top = (window.innerHeight - this.$el.clientHeight) / 2
            this.draggedElement.style.top = (top >= 0 ? top : 0) + 'px'
        })
    },
    beforeDestroy () {
        if (this.$el.parentNode === this.$root.$el) {
            this.$root.$el.removeChild(this.$el)
        }
    },
}
</script>

<style>
.es-list-popup {
    position: fixed;
    background: white;
    min-width: 500px;
    z-index: 2000;
    box-shadow: 0 5px 5px -3px rgba(0,0,0,.2),0 8px 10px 1px rgba(0,0,0,.14),0 3px 14px 2px rgba(0,0,0,.12);
}
.es-list-popup-header {
    padding: 8px 16px 0px 16px;
    user-select: none;
    cursor: move;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.es-list-popup-title {
    font-size: 18px;
    font-weight: 700;
}
.es-list-popup-content {
    padding: 16px;
}
</style>
