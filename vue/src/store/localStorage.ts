import { defineStore } from 'pinia';
import { useStorage } from '@vueuse/core';

interface ExpandedPanels {
    modules?: {
        [module: string]: {
            [panel: string]: {
                [sections: string]: []
            }
        }
    }
}

export const useLocalStorageStore = defineStore('localStorage', () => {
    const expandedPanels = useStorage<ExpandedPanels>('app.panels.expanded', { modules: {} })
    const sideMenuShrinked = useStorage<boolean>('app.sidebar.shrinked', false)

    function getPanelSections(module: string, panel: string): Array<number | string> {
        if (!expandedPanels.value.modules[module]) {
            expandedPanels.value.modules[module] = {}
        }
        if (!expandedPanels.value.modules[module][panel]) {
            expandedPanels.value.modules[module][panel] = { sections: [] }
        }
        return expandedPanels.value.modules[module][panel].sections
    }

    function setPanelSections(module: string, panel: string, sections: Array<number | string>) {
        if (!expandedPanels.value.modules[module]) {
            expandedPanels.value.modules[module] = {}
        }
        expandedPanels.value.modules[module][panel] = { sections }
    }

    function hasPanelSections(module: string, panel: string): boolean {
        console.error(expandedPanels.value)
        return !!(expandedPanels.value.modules[module] && expandedPanels.value.modules[module][panel])
    }

    return {
        getPanelSections,
        setPanelSections,
        hasPanelSections,
        sideMenuShrinked,
    }
})
