<template>
  <div class="dashboard">
    <v-overlay :value="overlay">
      <v-card width="400" class="mx-auto mt-5">
        <v-card-title>
          <h1 class="display-1">Adicionar produto</h1>
          <v-spacer></v-spacer>
          <v-btn
            icon
            @click="overlay = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <v-form>
            <v-text-field
              label="Nome do produto"
              prepend-icon="mdi-pencil"
              v-model="newProduct.name"
            />
            <v-text-field
              type="number"
              step="0.01"
              label="Preço"
              prepend-icon="mdi-cash"
              v-model="newProduct.price"
            />
          </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="success" @click="add()">Adicionar</v-btn>
        </v-card-actions>
      </v-card>
    </v-overlay>
    <v-toolbar dense>
      <v-toolbar-title>Loja</v-toolbar-title>
      <v-spacer></v-spacer>
      <div v-if="getUser">
        <v-btn icon v-if="getUser.isSeller === 1" @click="overlay = !overlay">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
        <v-btn color="red" text @click.prevent="logout">Sair</v-btn>
      </div>
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
            @click.prevent="buy(product.id, index)"
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
    overlay: false,
    newProduct: {
      name: '',
      price: '',
    },
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
    removeFromArray(array, value) {
      return array.filter(element => element.id !== value);
    },
    buy(productId, index) {
      axios.delete(`${this.getApiUrl}/products/${productId}`, {
        headers: {
          token: localStorage.token,
        },
      }).then(() => {
        this.products.splice(this.products.indexOf(this.products[index]), 1);
        alert('Compra realizada com sucesso!');
      });
    },
    add() {
      this.overlay = false;
      const data = {
        name: this.newProduct.name,
        price: Number(this.newProduct.price),
        user_id: Number(this.getUser.sub),
      };
      axios.post(`${this.getApiUrl}/products`, data, {
        headers: {
          token: localStorage.token,
        },
      }).then((res) => {
        this.products.push(res.data);
        this.newProduct.name = '';
        this.newProduct.price = '';
        alert('Produto adicionado com sucesso!');
      });
    },
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
