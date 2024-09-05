import { createStore } from "vuex";

export default createStore({
  state: {
    cart: [],
  },
  getters: {
    cartItems: (state) => state.cart,
    cartItemCount: (state) => state.cart.length,
    cartTotalPrice: (state) => {
      return state.cart.reduce((total, item) => {
        return total + parseFloat(item.price);
      }, 0);
    },
  },
  mutations: {
    ADD_TO_CART(state, product) {
      const productInCart = state.cart.find((item) => item._id === product._id);
      if (!productInCart) {
        state.cart.push({
          ...product,
          quantity: 1,
        });
      } else {
        productInCart.quantity++;
      }
    },
    REMOVE_FROM_CART(state, productId) {
      state.cart = state.cart.filter((item) => item._id !== productId);
    },
  },
  actions: {
    addToCart({ commit }, product) {
      commit("ADD_TO_CART", product);
    },
    removeFromCart({ commit }, productId) {
      commit("REMOVE_FROM_CART", productId);
    },
  },
});
