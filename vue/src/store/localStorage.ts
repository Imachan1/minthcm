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
    const expandedPanels = useStorage<ExpandedPanels>('app.panels.expanded', { modules: {}});

    return { 
        expandedPanels,
    }
});