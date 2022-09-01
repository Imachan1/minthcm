<template>
  <v-app>
    <v-main>
      <es-list></es-list>
    </v-main>
  </v-app>
</template>

<script>
import esList from './components/es-list';

export default {
  name: 'App',

  components: {
    esList
  }
};
</script>
