export default {
  state: {
    carts: {}, // Store carts by user ID
    guestCart: [], // Dedicated guest cart array
    currentUserId: null,
    isLoggedIn: false,
  },

  mutations: {
    SET_CURRENT_USER(state, userId) {
      state.currentUserId = userId;
    },

    ADD_TO_CART(state, product) {
      // For logged-in users
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
      }
      // For guest users
      else {
        const existingItem = state.guestCart.find(
          (item) => item.id === product.id
        );

        if (existingItem) {
          existingItem.quantity += product.quantity;
        } else {
          state.guestCart.push({ ...product, quantity: product.quantity || 1 });
        }
      }
    },

    UPDATE_CART_ITEM(state, { productId, quantity }) {
      // For logged-in users
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
      }
      // For guest users
      else {
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
    },

    REMOVE_FROM_CART(state, productId) {
      // For logged-in users
      if (state.currentUserId) {
        state.carts[state.currentUserId] = state.carts[
          state.currentUserId
        ].filter((item) => item.id !== productId);
      }
      // For guest users
      else {
        state.guestCart = state.guestCart.filter(
          (item) => item.id !== productId
        );
      }
    },

    CLEAR_CART(state) {
      // For logged-in users
      if (state.currentUserId) {
        state.carts[state.currentUserId] = [];
      }
      // For guest users
      else {
        state.guestCart = [];
      }
    },

    SET_LOGGED_IN(state, { status, userId }) {
      // When logging in, merge guest cart with user's cart
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

        // Clear guest cart after merging
        state.guestCart = [];
      }

      state.isLoggedIn = status;
      state.currentUserId = status ? userId : null;
    },
  },

  actions: {
    login({ commit }, userId) {
      commit("SET_LOGGED_IN", { status: true, userId });
    },

    logout({ commit }) {
      commit("SET_LOGGED_IN", { status: false, userId: null });
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
  },
};
