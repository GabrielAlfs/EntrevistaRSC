<template>
  <div class="dashboard">
    <h1>Dashboard</h1>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'dashboard',
  data: () => ({
    //
  }),
  computed: {
    getApiUrl() {
      return this.$store.getters.getApiUrl;
    },
  },
  mounted() {
    axios.get(`${this.getApiUrl}/users`, {
      headers: {
        token: localStorage.token,
      },
    }).then((res) => {
      console.log(res.data);
    });
  },
};
</script>

<style>

</style>
