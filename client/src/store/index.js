import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";

export default createStore({
  state: {
    cart: [],
    currentPage: 1,
    totalPages: 0,
    isModalOpen: false,
    review: {
      name: "",
      rating: 0,
      title: "",
      content: "",
    },
    user: null, // Add user state to store the logged-in user data
  },
  getters: {
    cartItems: (state) => state.cart,
    cartItemCount: (state) =>
      state.cart.reduce(
        (count, item) => count + (parseInt(item.quantity) || 0),
        0
      ),
    cartTotalPrice: (state) => {
      const total = state.cart.reduce(
        (total, item) =>
          total +
          (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0),
        0
      );
      return total.toFixed(2);
    },
    currentPage: (state) => state.currentPage,
    totalPages: (state) => state.totalPages,

    isModalOpen: (state) => state.isModalOpen,
    reviewData: (state) => state.review,

    // Getter to check if the user is authenticated
    isAuthenticated: (state) => !!state.user,

    // Getter to extract the first name from the 'name' field
    userFirstName: (state) => {
      if (state.user && state.user.name) {
        return state.user.name.split(" ")[0]; // Get the first part of the name
      }
      return ""; // Return empty string if no name found
    },
  },
  mutations: {
    ADD_TO_CART(state, product) {
      const item = state.cart.find((cartItem) => cartItem.id === product.id);
      if (item) {
        item.quantity += 1;
      } else {
        state.cart.push({ ...product, quantity: 1 });
      }
    },
    REMOVE_FROM_CART(state, id) {
      state.cart = state.cart.filter((item) => item.id !== id);
    },

    SET_USER(state, user) {
      state.user = user; // Store the user data
    },
    LOGOUT_USER(state) {
      state.user = null; // Clear the user data on logout
    },
  },
  actions: {
    addToCart({ commit }, product) {
      commit("ADD_TO_CART", product);
    },
    removeFromCart({ commit }, id) {
      commit("REMOVE_FROM_CART", id);
    },

    // Action to fetch the user from the API
    async fetchUser({ commit }) {
      try {
        const response = await fetch("http://localhost:8000/api/user", {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`, // Include token if needed
          },
        });

        if (!response.ok) {
          throw new Error("Failed to fetch user data.");
        }

        const user = await response.json();
        commit("SET_USER", user); // Store user data in Vuex
      } catch (error) {
        console.error("Error fetching user data:", error);
      }
    },

    logout({ commit }) {
      commit("LOGOUT_USER");
      localStorage.removeItem("token"); // Clear token on logout
    },
  },
  plugins: [
    createPersistedState({
      storage: window.localStorage,
      reducer: (state) => ({
        cart: state.cart,
        currentPage: state.currentPage,
        totalPages: state.totalPages,
        isModalOpen: state.isModalOpen,
      }),
    }),
  ],
});
