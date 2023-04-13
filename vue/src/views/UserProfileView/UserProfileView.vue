<template>
    <h1>User Profile</h1>
    <v-btn color="primary" @click="emitHello">asdasd</v-btn>
</template>

<script setup lang="ts">
function emitHello() {
    console.log('emit hello')
}
</script>
