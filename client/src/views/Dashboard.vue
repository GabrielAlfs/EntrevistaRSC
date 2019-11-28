<template>
  <div class="dashboard">
    <v-toolbar dense>
      <v-toolbar-title>Loja</v-toolbar-title>
      <v-spacer></v-spacer>

      <v-btn icon v-if="getUser">
        <v-icon v-if="getUser.isSeller === 1">mdi-plus</v-icon>
      </v-btn>

    </v-toolbar>
    <div class="d-flex flex-wrap justify-space-around">
      <v-card
        v-for="(product, index) in products"
        :key="index"
        class="mb-2 mt-2"
        max-width="400"
      >
        <v-img
          class="white--text align-end"
          height="200px"
          src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
        >
          <v-card-title>{{product.name.toUpperCase()}}</v-card-title>
        </v-img>

        <v-card-subtitle class="pb-0">Vendedor: Nº {{product.user_id}}</v-card-subtitle>

        <v-card-text class="text--primary">
          <div>Lorem Ipsum</div>

          <div>Lorem Ipsum dolor sit amet</div>


        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <div><strong>R$ {{product.price.toFixed(2).replace('.', ',')}}</strong></div>
          <v-spacer></v-spacer>
          <v-btn
            color="success"
            text
          >
            Comprar
          </v-btn>
        </v-card-actions>
      </v-card>
    </div>
    <div class="mb-12">&nbsp;</div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'dashboard',
  data: () => ({
    products: [],
  }),
  computed: {
    getApiUrl() {
      return this.$store.getters.getApiUrl;
    },
    getUser() {
      return this.$store.getters.getUser;
    },
  },
  methods: {
    logout() {
      localStorage.removeItem('token');
      this.$router.push('/');
    },
    setUser(user) {
      return this.$store.commit('setUser', user);
    },
  },
  mounted() {
    axios.get(`${this.getApiUrl}/users/active`, {
      headers: {
        token: localStorage.token,
      },
    }).then((res) => {
      if (res.data) {
        this.setUser(res.data);
      } else {
        this.logout();
      }
    });
    axios.get(`${this.getApiUrl}/products`, {
      headers: {
        token: localStorage.token,
      },
    }).then((res) => {
      this.products = res.data;
    });
  },
};
</script>

<style>

</style>
