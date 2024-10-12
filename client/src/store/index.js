import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import router from "../../router/index"; // Import router

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
    user: null, // Store the logged-in user data
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
    isAuthenticated: (state) => !!state.user,
    userFirstName: (state) => {
      if (state.user && state.user.name) {
        return state.user.name.split(" ")[0];
      }
      return "";
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
      state.user = user;
    },
    LOGOUT_USER(state) {
      state.user = null;
    },
    INCREASE_ITEM_QUANTITY(state, id) {
      const item = state.cart.find((cartItem) => cartItem.id === id);
      if (item) {
        item.quantity += 1;
      }
    },
    DECREASE_ITEM_QUANTITY(state, id) {
      const item = state.cart.find((cartItem) => cartItem.id === id);
      if (item && item.quantity > 1) {
        item.quantity -= 1;
      }
    },
  },
  actions: {
    addToCart({ commit }, product) {
      commit("ADD_TO_CART", product);
    },
    removeFromCart({ commit }, id) {
      commit("REMOVE_FROM_CART", id);
    },
    increaseItemQuantity({ commit }, id) {
      commit("INCREASE_ITEM_QUANTITY", id);
    },
    decreaseItemQuantity({ commit }, id) {
      commit("DECREASE_ITEM_QUANTITY", id);
    },
    async fetchUser({ commit }) {
      try {
        const response = await fetch(`${import.meta.env.VITE_API_URL}/user`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        if (!response.ok) {
          throw new Error("Failed to fetch user data.");
        }

        const user = await response.json();
        commit("SET_USER", user);
      } catch (error) {
        console.error("Error fetching user data:", error);
      }
    },
    async logout({ commit }) {
      try {
        await fetch(`${import.meta.env.VITE_API_URL}/logout`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        commit("LOGOUT_USER");
        localStorage.removeItem("token");
        router.push("/login");
      } catch (error) {
        console.error("Error logging out:", error);
      }
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
        user: state.user, // Persist user state
      }),
    }),
  ],
});
