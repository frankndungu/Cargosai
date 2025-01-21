import axios from "axios";

export default {
  state: {
    namespace: true,
    carts: JSON.parse(localStorage.getItem("userCarts") || "{}"),
    guestCart: JSON.parse(localStorage.getItem("guestCart") || "[]"),
    currentUserId: null,
    isLoggedIn: false,
    isGuestCheckout: false, // New state to track guest checkout
  },

  mutations: {
    SET_GUEST_CHECKOUT(state, status) {
      state.isGuestCheckout = status;
    },

    SET_CURRENT_USER(state, userId) {
      state.currentUserId = userId;
    },

    SAVE_CARTS(state) {
      console.log(
        "Saving carts to localStorage:",
        state.carts,
        state.guestCart
      );
      localStorage.setItem("userCarts", JSON.stringify(state.carts));
      localStorage.setItem("guestCart", JSON.stringify(state.guestCart));
    },

    MERGE_CARTS(state) {
      if (state.currentUserId && state.guestCart.length > 0) {
        if (!state.carts[state.currentUserId]) {
          state.carts[state.currentUserId] = [];
        }

        state.guestCart.forEach((guestItem) => {
          const existingItemIndex = state.carts[state.currentUserId].findIndex(
            (item) => item.id === guestItem.id
          );

          if (existingItemIndex !== -1) {
            state.carts[state.currentUserId][existingItemIndex].quantity +=
              guestItem.quantity;
          } else {
            state.carts[state.currentUserId].push({
              ...guestItem,
              price: parseFloat(guestItem.price),
            });
          }
        });

        state.guestCart = [];
        this.commit("SAVE_CARTS");
      }
    },

    ADD_TO_CART(state, product) {
      // Ensure price is a valid number
      const validPrice =
        typeof product.price === "string"
          ? parseFloat(product.price)
          : Number(product.price);

      if (isNaN(validPrice)) {
        console.error("Invalid price for product:", product);
        return; // Don't add products with invalid prices
      }

      const cartItem = {
        ...product,
        price: validPrice,
        quantity: parseInt(product.quantity) || 1,
      };

      if (state.currentUserId) {
        if (!state.carts[state.currentUserId]) {
          state.carts[state.currentUserId] = [];
        }

        const userCart = state.carts[state.currentUserId];
        const existingItem = userCart.find((item) => item.id === product.id);

        if (existingItem) {
          existingItem.quantity += cartItem.quantity;
        } else {
          userCart.push(cartItem);
        }
      } else {
        const existingItem = state.guestCart.find(
          (item) => item.id === product.id
        );

        if (existingItem) {
          existingItem.quantity += cartItem.quantity;
        } else {
          state.guestCart.push(cartItem);
        }
      }

      this.commit("SAVE_CARTS");
    },

    UPDATE_CART_ITEM(state, { productId, quantity }) {
      const parsedQuantity = parseInt(quantity);
      if (isNaN(parsedQuantity)) {
        console.error("Invalid quantity update:", quantity);
        return;
      }

      if (state.currentUserId) {
        const userCart = state.carts[state.currentUserId];
        const item = userCart.find((item) => item.id === productId);

        if (item) {
          item.quantity += parsedQuantity;
          if (item.quantity <= 0) {
            state.carts[state.currentUserId] = userCart.filter(
              (item) => item.id !== productId
            );
          }
        }
      } else {
        const item = state.guestCart.find((item) => item.id === productId);

        if (item) {
          item.quantity += parsedQuantity;
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

    SET_CART_TOTAL(state, total) {
      const validTotal = parseFloat(total);
      if (isNaN(validTotal)) {
        console.error("Invalid cart total:", total);
        return;
      }

      if (state.currentUserId) {
        if (!state.carts[state.currentUserId]) {
          state.carts[state.currentUserId] = [];
        }
        state.carts[state.currentUserId].total = validTotal;
      } else {
        state.guestCartTotal = validTotal;
      }

      this.commit("SAVE_CARTS");
    },

    CLEAR_CART(state) {
      if (state.currentUserId) {
        delete state.carts[state.currentUserId];
        localStorage.removeItem("userCarts");
      } else {
        state.guestCart = [];
        localStorage.removeItem("guestCart");
      }
      console.log("Cart cleared from state and localStorage.");
    },

    // Modify SET_LOGGED_IN to handle guest checkout state
    SET_LOGGED_IN(state, { status, userId }) {
      state.isLoggedIn = status;
      state.currentUserId = status ? userId : null;

      if (status) {
        state.isGuestCheckout = false; // Reset guest checkout when logging in
        this.commit("MERGE_CARTS");
      }

      this.commit("SAVE_CARTS");
      console.log("Login status updated. User ID:", state.currentUserId);
    },
  },

  actions: {
    async fetchCurrentUser({ commit }) {
      const token = localStorage.getItem("token");

      if (!token) {
        commit("SET_LOGGED_IN", { status: false, userId: null });
        return;
      }

      try {
        const response = await axios.get(
          `${import.meta.env.VITE_API_URL}/user`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.data && response.data.id) {
          commit("SET_LOGGED_IN", { status: true, userId: response.data.id });
        } else {
          localStorage.removeItem("token");
          commit("SET_LOGGED_IN", { status: false, userId: null });
        }
      } catch (error) {
        if (error.response && error.response.status === 401) {
          localStorage.removeItem("token");
        }
        console.error("Error fetching current user:", error);
        commit("SET_LOGGED_IN", { status: false, userId: null });
      }
    },

    login({ commit, dispatch }, credentials) {
      return new Promise((resolve, reject) => {
        axios
          .post(`${import.meta.env.VITE_API_URL}/login`, credentials)
          .then((response) => {
            const { token, user } = response.data;
            localStorage.setItem("token", token);
            commit("SET_LOGGED_IN", { status: true, userId: user.id });
            dispatch("fetchCurrentUser");
            resolve(response);
          })
          .catch((error) => {
            console.error("Login error:", error);
            reject(error);
          });
      });
    },

    logout({ commit }) {
      localStorage.removeItem("token");
      localStorage.removeItem("userCarts");
      localStorage.removeItem("guestCart");
      commit("SET_LOGGED_IN", { status: false, userId: null });
      commit("CLEAR_CART");
    },

    addToCart({ commit }, product) {
      commit("ADD_TO_CART", product);
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

    setGuestCheckout({ commit }, status) {
      commit("SET_GUEST_CHECKOUT", status);
    },
  },

  getters: {
    isGuestCheckout: (state) => state.isGuestCheckout,

    cartItems(state) {
      const items = state.currentUserId
        ? state.carts[state.currentUserId] || []
        : state.guestCart;
      console.log("Retrieved cart items:", items);
      return items;
    },

    cartTotal(state) {
      const userCart = state.currentUserId
        ? state.carts[state.currentUserId] || []
        : state.guestCart;

      return userCart.reduce((total, item) => {
        const itemPrice = parseFloat(item.price);
        const itemQuantity = parseInt(item.quantity);

        if (isNaN(itemPrice) || isNaN(itemQuantity)) {
          console.error("Invalid price or quantity for item:", item);
          return total;
        }

        return total + itemPrice * itemQuantity;
      }, 0);
    },

    isLoggedIn(state) {
      return state.isLoggedIn;
    },

    cartItemCount(state) {
      const userCart = state.currentUserId
        ? state.carts[state.currentUserId] || []
        : state.guestCart;

      return userCart.reduce((total, item) => {
        const quantity = parseInt(item.quantity);
        return total + (isNaN(quantity) ? 0 : quantity);
      }, 0);
    },
  },
};
