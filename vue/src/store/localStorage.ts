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
    const expandedPanels = useStorage<ExpandedPanels>('app.panels.expanded', { modules: {} });

    function getPanelSections(module: string, panel: string): Array<number | string> {
        debugger
        if (!expandedPanels.value.modules[module]) {
            expandedPanels.value.modules[module] = {};
        }
        if (!expandedPanels.value.modules[module][panel]) {
            expandedPanels.value.modules[module][panel] = { sections: [] };
        }
        return expandedPanels.value.modules[module][panel].sections;
    }

    function setPanelSections(module: string, panel: string, sections: Array<number | string>) {
        debugger
        if (!expandedPanels.value.modules[module]) {
            expandedPanels.value.modules[module] = {};
        }
        expandedPanels.value.modules[module][panel] = { sections };
    }

    return {
        getPanelSections,
        setPanelSections,
    }
});