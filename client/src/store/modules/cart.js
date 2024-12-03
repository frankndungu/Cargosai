export default {
  state: {
    carts: JSON.parse(localStorage.getItem("userCarts") || "{}"),
    guestCart: JSON.parse(localStorage.getItem("guestCart") || "[]"),
    currentUserId: null,
    isLoggedIn: false,
  },

  mutations: {
    SET_CURRENT_USER(state, userId) {
      state.currentUserId = userId;
    },

    SAVE_CARTS(state) {
      localStorage.setItem("userCarts", JSON.stringify(state.carts));
      localStorage.setItem("guestCart", JSON.stringify(state.guestCart));
    },

    ADD_TO_CART(state, product) {
      if (state.currentUserId) {
        if (!state.carts[state.currentUserId]) {
          state.carts[state.currentUserId] = [];
        }

        const userCart = state.carts[state.currentUserId];
        const existingItem = userCart.find((item) => item.id === product.id);

        if (existingItem) {
          existingItem.quantity += product.quantity;
        } else {
          userCart.push({ ...product, quantity: product.quantity || 1 });
        }
      } else {
        const existingItem = state.guestCart.find(
          (item) => item.id === product.id
        );

        if (existingItem) {
          existingItem.quantity += product.quantity;
        } else {
          state.guestCart.push({ ...product, quantity: product.quantity || 1 });
        }
      }

      this.commit("SAVE_CARTS");
    },

    UPDATE_CART_ITEM(state, { productId, quantity }) {
      if (state.currentUserId) {
        const userCart = state.carts[state.currentUserId];
        const item = userCart.find((item) => item.id === productId);

        if (item) {
          item.quantity += quantity;
          if (item.quantity <= 0) {
            state.carts[state.currentUserId] = userCart.filter(
              (item) => item.id !== productId
            );
          }
        }
      } else {
        const item = state.guestCart.find((item) => item.id === productId);

        if (item) {
          item.quantity += quantity;
          if (item.quantity <= 0) {
            state.guestCart = state.guestCart.filter(
              (item) => item.id !== productId
            );
          }
        }
      }

      this.commit("SAVE_CARTS");
    },

    REMOVE_FROM_CART(state, productId) {
      if (state.currentUserId) {
        state.carts[state.currentUserId] = state.carts[
          state.currentUserId
        ].filter((item) => item.id !== productId);
      } else {
        state.guestCart = state.guestCart.filter(
          (item) => item.id !== productId
        );
      }

      this.commit("SAVE_CARTS");
    },

    CLEAR_CART(state) {
      if (state.currentUserId) {
        delete state.carts[state.currentUserId];
      } else {
        state.guestCart = [];
      }

      this.commit("SAVE_CARTS");
    },

    SET_LOGGED_IN(state, { status, userId }) {
      if (status && state.guestCart.length > 0) {
        if (!state.carts[userId]) {
          state.carts[userId] = [];
        }

        state.guestCart.forEach((guestItem) => {
          const existingItem = state.carts[userId].find(
            (item) => item.id === guestItem.id
          );

          if (existingItem) {
            existingItem.quantity += guestItem.quantity;
          } else {
            state.carts[userId].push(guestItem);
          }
        });

        state.guestCart = [];
      }

      state.isLoggedIn = status;
      state.currentUserId = status ? userId : null;

      this.commit("SAVE_CARTS");
    },
  },

  actions: {
    login({ commit }, userId) {
      commit("SET_LOGGED_IN", { status: true, userId });
    },

    logout({ commit }) {
      commit("SET_LOGGED_IN", { status: false, userId: null });
      localStorage.removeItem("userCarts");
      localStorage.removeItem("guestCart");
      commit("CLEAR_CART");
    },

    addToCart({ commit }, product) {
      const formattedProduct = {
        ...product,
        price: Number(product.price),
      };
      commit("ADD_TO_CART", formattedProduct);
    },

    updateCartItem({ commit }, { productId, quantity }) {
      commit("UPDATE_CART_ITEM", { productId, quantity });
    },

    removeFromCart({ commit }, productId) {
      commit("REMOVE_FROM_CART", productId);
    },

    clearCart({ commit }) {
      commit("CLEAR_CART");
    },
  },

  getters: {
    cartItems(state) {
      return state.currentUserId
        ? state.carts[state.currentUserId] || []
        : state.guestCart;
    },

    cartTotal(state) {
      const userCart = state.currentUserId
        ? state.carts[state.currentUserId] || []
        : state.guestCart;

      return userCart.reduce(
        (total, item) => total + item.price * item.quantity,
        0
      );
    },

    isLoggedIn(state) {
      return state.isLoggedIn;
    },

    cartItemCount(state) {
      const userCart = state.currentUserId
        ? state.carts[state.currentUserId] || []
        : state.guestCart;

      return userCart.reduce((total, item) => total + item.quantity, 0);
    },
  },
};
