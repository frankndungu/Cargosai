// store/modules/userStore.js
import { ref } from "vue";
import { createStore } from "vuex";

const userStore = createStore({
  state: {
    user: null, // Store user information
    isAuthenticated: false, // Authentication status
  },
  getters: {
    getUser: (state) => state.user,
    isAuthenticated: (state) => state.isAuthenticated,
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user;
      state.isAuthenticated = !!user; // Set authentication status based on user info
    },
    LOGOUT(state) {
      state.user = null;
      state.isAuthenticated = false; // Reset authentication status
    },
  },
  actions: {
    login({ commit }, user) {
      commit("SET_USER", user);
    },
    logout({ commit }) {
      commit("LOGOUT");
    },
  },
});

export default userStore;
