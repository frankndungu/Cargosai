import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";

export default createStore({
  state: {
    cart: [],
    currentPage: 1,
    totalPages: 0,
    isModalOpen: false, // To manage the modal state
    review: {
      name: "",
      rating: 0,
      title: "",
      content: "",
    },
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

    // New getters for modal and review state
    isModalOpen: (state) => state.isModalOpen,
    reviewData: (state) => state.review,
  },
  mutations: {
    ADD_TO_CART(state, product) {
      const item = state.cart.find((cartItem) => cartItem.id === product.id);
      if (item) {
        // If the product already exists in the cart, update its quantity
        item.quantity += 1;
      } else {
        // If it's a new product, add it to the cart with an initial quantity
        state.cart.push({ ...product, quantity: 1 });
      }
    },
    REMOVE_FROM_CART(state, id) {
      state.cart = state.cart.filter((item) => item.id !== id);
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
    SET_CURRENT_PAGE(state, page) {
      state.currentPage = page;
    },
    SET_TOTAL_PAGES(state, pages) {
      state.totalPages = pages;
    },

    // Mutations for handling modal and review state
    OPEN_MODAL(state) {
      state.isModalOpen = true;
    },
    CLOSE_MODAL(state) {
      state.isModalOpen = false;
    },
    UPDATE_REVIEW(state, reviewData) {
      state.review = { ...state.review, ...reviewData };
    },
    UPDATE_REVIEW_RATING(state, rating) {
      state.review.rating = rating;
    },
    RESET_REVIEW(state) {
      state.review = {
        name: "",
        rating: 0,
        title: "",
        content: "",
      };
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

    // Actions for handling modal and review state
    openModal({ commit }) {
      commit("OPEN_MODAL");
    },
    closeModal({ commit }) {
      commit("CLOSE_MODAL");
    },
    updateReview({ commit }, reviewData) {
      commit("UPDATE_REVIEW", reviewData);
    },
    updateReviewRating({ commit }, rating) {
      commit("UPDATE_REVIEW_RATING", rating);
    },
    resetReview({ commit }) {
      commit("RESET_REVIEW");
    },
  },
  plugins: [
    createPersistedState({
      storage: window.localStorage,
      reducer: (state) => ({
        cart: state.cart, // Only persist cart
        currentPage: state.currentPage,
        totalPages: state.totalPages,
        isModalOpen: state.isModalOpen,
        // Exclude the review state from being persisted
      }),
    }),
  ],
});
