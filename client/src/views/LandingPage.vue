<template>
  <div class="landingPage">
    <v-card width="400" class="mx-auto mt-5">
      <div v-if="toggleLogin">
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
          <v-btn color="success" @click.prevent="toggleLogin = false">Criar uma conta</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="info" @click.prevent="login">{{buttonLogin}}</v-btn>
        </v-card-actions>
      </div>
      <div v-if="!toggleLogin">
        <v-card-title>
          <h1 class="display-1">Criar uma conta</h1>
        </v-card-title>
        <v-card-text>
          <v-form>
            <v-text-field
              label="Nome"
              prepend-icon="mdi-pencil"
              v-model="user.name"
            />
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
            <v-text-field
              :type="showPassword ? 'text' : 'password'"
              label="Confirmar senha"
              prepend-icon="mdi-lock"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
              v-model="user.confirmPassword"
            />
            <v-select
              prepend-icon="mdi-tennis"
              :items="['Comprador', 'Vendedor']"
              label="Tipo de conta"
              v-model="user.accountType"
            ></v-select>
          </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn color="info" @click.prevent="toggleLogin = true">Ja tenho uma conta</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="success" @click.prevent="register">Registrar</v-btn>
        </v-card-actions>
      </div>
    </v-card>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'landingPage',
  data: () => ({
    toggleLogin: true,
    buttonLogin: 'Logar',
    showPassword: false,
    user: {
      name: '',
      email: '',
      password: '',
      confirmPassword: '',
      accountType: '',
      isSeller: 0,
    },
  }),
  computed: {
    getApiUrl() {
      return this.$store.getters.getApiUrl;
    },
  },
  methods: {
    setUser(user) {
      return this.$store.commit('setUser', user);
    },
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
    validUser() {
      if (this.user.password !== this.user.confirmPassword) {
        alert('As senhas digitadas estão diferentes!');
        return false;
      }

      if (this.user.accountType === 'Vendedor') this.user.isSeller = 1;

      // Adicionar validação de usuario

      return true;
    },
    register() {
      if (this.validUser()) {
        const body = {
          name: this.user.name,
          email: this.user.email,
          password: this.user.password,
          isSeller: this.user.isSeller,
        };
        axios.post(`${this.getApiUrl}/register`, body).then(() => {
          alert('Conta cadastrada com sucesso, você já pode fazer o login!');
          this.toggleLogin = true;
        }).catch(() => {
          alert('Esse e-mail já está cadastrado, tente fazer o login!');
        });
      }
    },
  },
};
</script>
