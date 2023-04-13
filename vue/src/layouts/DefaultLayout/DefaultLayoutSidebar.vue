<template>
    <v-navigation-drawer
        color="#444444"
        expand-on-hover
        :rail="$vuetify.display.mdAndDown"
        style="top: 60px; color: white"
        permanent
    >
        <v-list
            density="compact"
            nav
            style="background: rgb(var(--v-theme-primary)) !important"
        >
            <v-list-item
                prepend-icon="mdi-plus"
                title="Utwórz"
                value="myfiles"
                to="/Candidates/EditView"
                :active="false"
            />
            <v-list-item
                prepend-icon="mdi-eye"
                title="Widok szczegółowy"
                value="shared"
                to="/Candidates/DetailView/15894e90-fc9c-fd98-7f99-5ce299796e08"
                :active="false"
            />
            <v-list-item
                prepend-icon="mdi-calendar"
                title="Lista"
                value="starred"
                to="/Candidates"
                :active="false"
            />
        </v-list>
        <v-divider />
        <v-text-field
            style="padding: 12px 16px 12px 16px"
            ref="searchField"
            density="compact"
            hide-details
            placeholder="Szukaj"
            variant="outlined"
            prepend-icon="mdi-magnify"
        />
        <v-divider />
        <div class="section-label">
            <span>Ulubione</span>
        </div>
        <v-list density="compact" nav>
            <v-list-item
                v-for="favorite in backend.favorites"
                :key="favorite.id"
                prepend-icon="mdi-star"
                :title="favorite.item_summary"
                :value="favorite.id"
                :to="`/${favorite.module_name}/DetailView/${favorite.id}`"
                :active="false"
            />
        </v-list>
        <v-divider />
        <div class="section-label">
            <span v-text="'Ostatnio oglądane'" />
        </div>
        <v-list density="compact" nav>
            <v-list-item
                v-for="recent in backend.recents"
                :key="recent.item_id"
                prepend-icon="mdi-clock"
                :title="recent.item_summary"
                :value="recent.item_id"
                :to="`/${recent.module_name}/DetailView/${recent.item_id}`"
                :active="false"
            />
        </v-list>
    </v-navigation-drawer>
</template>

<script setup lang="ts">
import { useBackendStore } from '@/store/backend'

const backend = useBackendStore()
</script>

<style scoped lang="scss">
.section-label {
    overflow-x: hidden;
    text-overflow: ellipsis;
    padding: 8px 12px 0 12px;
    span {
        overflow-x: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
}
</style>
