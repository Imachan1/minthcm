<template>
    <div
        :class="{
            drawer: true,
            closed: !ux.drawer,
        }"
    >
        <div class="drawer-nav">
            <template v-for="drawer in bundle.drawers" :key="drawer.key">
                <v-badge
                    v-if="drawer.isAvaliable?.()"
                    :content="drawer.badge?.()"
                    color="error"
                    location="bottom start"
                    :model-value="!!drawer.badge?.()"
                    @click="setDrawer(drawer)"
                    @keydown.enter="setDrawer(drawer)"
                    @keydown.space="setDrawer(drawer)"
                    :name="drawer.key"
                    :id="drawer.key"
                    :aria-label="languages.label(drawer.label)"
                    :aria-description="languages.label(drawer.descriptionLabel)"
                    :aria-describedby="drawer.key + 'drawer-help'"
                >
                    <MintButton :icon="drawer.icon" variant="nav" :active="ux.drawer === drawer.key" />
                </v-badge>
                <p :id="drawer.key + 'drawer-help'" :name="drawer.key + 'drawer-help'" hidden>{{languages.label(drawer.descriptionLabel)}}</p>
            </template>
            <MintButton 
                    v-if="ux.drawer" 
                    @click="ux.drawer = null" 
                    icon="mdi-close" 
                    variant="nav"
                    @keydown.enter="ux.drawer = null"
                    @keydown.space="ux.drawer = null"
                    name='close-mint-drawer'
                    id="close-mint-drawer"
                    :aria-label="languages.label('LBL_CLOSE_DRAWER')"
                    :aria-description="languages.label('LBL_CLOSE_DRAWER_COMMENT')"
                    aria-describedby='close-mint-drawer-help'
            />
            <p id="close-mint-drawer-help" name="close-mint-drawer-help" hidden>{{languages.label('LBL_CLOSE_DRAWER_COMMENT')}}</p>
        </div>
        <v-slide-x-transition hide-on-leave>
            <div v-if="ux.drawer" class="drawer-content" ref="drawerContentRef" @scroll="handleScroll">
                <template v-for="drawer in bundle.drawers" :key="drawer.key">
                    <component v-if="ux.drawer === drawer.key" :is="drawer.component" />
                </template>
            </div>
        </v-slide-x-transition>
    </div>
</template>

<script setup lang="ts">
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useUxStore } from '@/store/ux'
import { computed, ref } from 'vue'
import bundle from '@/bundler'
import { useLanguagesStore } from '@/store/languages'

const ux = useUxStore()
const languages = useLanguagesStore()
const drawerContentRef = ref<HTMLElement | null>(null)

const activeDrawer = computed(() => bundle.drawers.find((drawer: any) => drawer.key === ux.drawer))

function handleScroll() {
    if (typeof activeDrawer.value?.onScroll === 'function') {
        activeDrawer.value.onScroll(drawerContentRef.value)
    }
}

function setDrawer(drawer: any) {
    ux.drawer = ux.drawer === drawer.key ? null : drawer.key
}

</script>

<style scoped lang="scss">
.drawer {
    position: fixed;
    z-index: 1000;
    top: var(--v-top-nav-height);
    right: 0px;
    height: calc(100vh - var(--v-top-nav-height));
    box-shadow: 0px 1px 32px #0099761a;

    .drawer-content {
        width: var(--v-drawer-width);
        background: rgb(var(--v-theme-surface));
        height: 100%;
        overflow: auto;
    }

    .drawer-nav {
        padding: 8px 0px 8px 8px;
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 50%;
        transform: translate(-100%, -50%);
        gap: 4px;
        z-index: -1;
        background: rgb(var(--v-theme-surface));
        border-radius: 32px 0px 0px 32px;
        box-shadow: 0px 1px 6px #00000029;

        &::before,
        &::after {
            content: '';
            width: 32px;
            height: 32px;
            position: absolute;
            background: inherit;
            right: 0px;
            mask: radial-gradient(circle at center, transparent 16px, black 0%);
            -webkit-mask: radial-gradient(circle at center, transparent 16px, black 0%);
        }
        &::before {
            top: -32px;
            border-radius: 50% 50% 0px 50%;
        }
        &::after {
            bottom: -32px;
            border-radius: 50% 0px 50% 50%;
        }
    }
}

.drawer.closed {
    .drawer-nav {
        padding-right: 8px;
        transition: transform 0.3s ease;
        transform: translate(calc(-100% + 28px), -50%);
        &:hover {
            transform: translate(-100%, -50%);
        }
    }
}

.v-badge {
    :deep(.v-badge__badge) {
        outline: 2px solid #fff;
        margin-top: -8px;
        font-weight: 600;
    }
}
</style>
