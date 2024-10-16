// store/cartStore.js
const state = {
  cart: [],
  currentPage: 1,
  totalPages: 0,
  isModalOpen: false,
};

const getters = {
  cartItems: (state) => state.cart,
  cartItemCount: (state) =>
    state.cart.reduce(
      (count, item) => count + (parseInt(item.quantity) || 0),
      0
    ),
  cartTotalPrice: (state) => {
    const total = state.cart.reduce(
      (total, item) =>
        total + (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0),
      0
    );
    return total.toFixed(2);
  },
  currentPage: (state) => state.currentPage,
  totalPages: (state) => state.totalPages,
  isModalOpen: (state) => state.isModalOpen,
};

const mutations = {
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
  SET_MODAL_OPEN(state, isOpen) {
    state.isModalOpen = isOpen;
  },
};

const actions = {
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
  openModal({ commit }) {
    commit("SET_MODAL_OPEN", true);
  },
  closeModal({ commit }) {
    commit("SET_MODAL_OPEN", false);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
