import axios from "axios";

export default {
  state: {
    namespace: true,
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
      console.log(
        "Saving carts to localStorage:",
        state.carts,
        state.guestCart
      );
      localStorage.setItem("userCarts", JSON.stringify(state.carts));
      localStorage.setItem("guestCart", JSON.stringify(state.guestCart));
    },

    MERGE_CARTS(state) {
      // If there's a logged-in user and a guest cart, merge the carts
      if (state.currentUserId && state.guestCart.length > 0) {
        // Ensure user's cart exists
        if (!state.carts[state.currentUserId]) {
          state.carts[state.currentUserId] = [];
        }

        // Merge guest cart items into user's cart
        state.guestCart.forEach((guestItem) => {
          const existingItemIndex = state.carts[state.currentUserId].findIndex(
            (item) => item.id === guestItem.id
          );

          if (existingItemIndex !== -1) {
            // If item exists, update quantity
            state.carts[state.currentUserId][existingItemIndex].quantity +=
              guestItem.quantity;
          } else {
            // If item doesn't exist, add it
            state.carts[state.currentUserId].push(guestItem);
          }
        });

        // Clear guest cart after merging
        state.guestCart = [];
        this.commit("SAVE_CARTS");
      }
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

    SET_CART_TOTAL(state, total) {
      if (state.currentUserId) {
        // Assuming cart total is stored per user, update the relevant user's total
        if (!state.carts[state.currentUserId]) {
          state.carts[state.currentUserId] = [];
        }
        state.carts[state.currentUserId].total = total; // Update total for the user's cart
      } else {
        state.guestCartTotal = total; // Update guest cart total
      }

      this.commit("SAVE_CARTS"); // Ensure changes persist in localStorage
    },

    CLEAR_CART(state) {
      if (state.currentUserId) {
        delete state.carts[state.currentUserId];
        localStorage.removeItem("userCarts"); // Clear user carts from localStorage
      } else {
        state.guestCart = [];
        localStorage.removeItem("guestCart"); // Clear guest cart from localStorage
      }
      console.log("Cart cleared from state and localStorage.");
    },

    SET_LOGGED_IN(state, { status, userId }) {
      state.isLoggedIn = status;
      state.currentUserId = status ? userId : null;

      if (status) {
        this.commit("MERGE_CARTS"); // Merge carts when user logs in
      }

      this.commit("SAVE_CARTS");
      console.log("User logged in. User ID:", state.currentUserId);
    },
  },

  actions: {
    async fetchCurrentUser({ commit }) {
      const token = localStorage.getItem("token");
      console.log("Token:", token); // Check if the token is valid

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
          commit("SET_LOGGED_IN", { status: false, userId: null });
        }
      } catch (error) {
        console.error("Error fetching current user:", error);
        commit("SET_LOGGED_IN", { status: false, userId: null });
      }
    },

    login({ commit, dispatch }, credentials) {
      return new Promise((resolve, reject) => {
        // Assuming you have a login API call
        axios
          .post(`${import.meta.env.VITE_API_URL}/login`, credentials)
          .then((response) => {
            const { token, user } = response.data;

            // Store token in localStorage
            localStorage.setItem("token", token);

            // Set logged-in state and user ID
            commit("SET_LOGGED_IN", { status: true, userId: user.id });

            // Fetch current user details
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
      commit("SET_LOGGED_IN", { status: false, userId: null });
      localStorage.removeItem("userCarts");
      localStorage.removeItem("guestCart");
      localStorage.removeItem("token"); // Remove the authentication token
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
