<template>
  <div class="landingPage">
    <v-card width="400" class="mx-auto mt-5">
      <v-card-title>
        <h1 class="display-1">Login</h1>
      </v-card-title>
      <v-card-text>
        <v-form>
          <v-text-field
            label="E-mail"
            prepend-icon="mdi-account-circle"
            v-model="user.email"
          />
          <v-text-field
            :type="showPassword ? 'text' : 'password'"
            label="Senha"
            prepend-icon="mdi-lock"
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="showPassword = !showPassword"
            v-model="user.password"
          />
        </v-form>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn color="success">Criar uma conta</v-btn>
        <v-spacer></v-spacer>
        <v-btn color="info" @click.prevent="login">{{buttonLogin}}</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'landingPage',
  data: () => ({
    buttonLogin: 'Logar',
    showPassword: false,
    user: {
      email: '',
      password: '',
    },
  }),
  computed: {
    getApiUrl() {
      return this.$store.getters.getApiUrl;
    },
  },
  methods: {
    login() {
      this.buttonLogin = 'Carregando ...';
      const body = {
        email: this.user.email,
        password: this.user.password,
      };
      axios.post(`${this.getApiUrl}/auth/login`, body).then((res) => {
        localStorage.token = res.data.token;
        this.$router.push('/dashboard');
      }).catch(() => {
        alert('Email ou senha incorretos!');
      }).finally(() => {
        this.buttonLogin = 'Logar';
      });
    },
  },
};
</script>
