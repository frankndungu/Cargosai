import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";

export default createStore({
  state: {
    cart: [],
    currentPage: 1,
    totalPages: 0,
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
  },
  mutations: {
    ADD_TO_CART(state, product) {
      const item = state.cart.find((cartItem) => cartItem._id === product._id);
      if (item) {
        item.quantity += 1;
      } else {
        state.cart.push({ ...product, quantity: 1 });
      }
    },
    REMOVE_FROM_CART(state, id) {
      state.cart = state.cart.filter((item) => item._id !== id);
    },
    INCREASE_ITEM_QUANTITY(state, id) {
      const item = state.cart.find((cartItem) => cartItem._id === id);
      if (item) {
        item.quantity += 1;
      }
    },
    DECREASE_ITEM_QUANTITY(state, id) {
      const item = state.cart.find((cartItem) => cartItem._id === id);
      if (item && item.quantity > 1) {
        item.quantity -= 1;
      }
    },
    SET_CURRENT_PAGE(state, page) {
      state.currentPage = page;
    },
    SET_TOTAL_PAGES(state, pages) {
      state.totalPages = pages;
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
    setCurrentPage({ commit }, page) {
      commit("SET_CURRENT_PAGE", page);
    },
    setTotalPages({ commit }, pages) {
      commit("SET_TOTAL_PAGES", pages);
    },
  },
  plugins: [
    createPersistedState({
      storage: window.localStorage, // Or sessionStorage
    }),
  ],
});
