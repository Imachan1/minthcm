<template>
    <nav class="guest-navbar">
        <img height="35" src="../../assets/mint_logo_white.svg" />
    </nav>
    <slot></slot>
    <footer>
        <span>© Reinvented by MintHCM</span>
        <span>© Supercharged by SuiteCRM</span>
        <span>© Powered By SugarCRM</span>
    </footer>
</template>

<script setup lang="ts"></script>

<style scoped lang="scss">
.guest-navbar {
    z-index: 1005;
    position: fixed;
    top: 0px;
    width: 100%;
    color: white;
    background: rgb(var(--v-theme-primary));
    height: 60px;
    display: flex;
    padding: 0 16px;
    gap: 16px;
    align-items: center;
}
footer {
    height: 40px;
    background: #ccc;
    font-size: 12px;
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 0 32px;
}
</style>
