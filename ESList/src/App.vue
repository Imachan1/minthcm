<template>
  <v-app>
    <v-main>
      <es-list></es-list>
    </v-main>
  </v-app>
</template>

<script>
import EsList from './components/es-list';
import data from './data'

export default {
  name: 'App',

  components: {
    EsList
  },

  data: () => ({
    data: data
  }),
};
</script>
