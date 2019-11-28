import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    API_URL: 'http://localhost:8081',
    user: null,
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
  },
  actions: {
  },
  modules: {
  },
  getters: {
    getApiUrl: state => state.API_URL,
    getUser: state => state.user,
  },
});
