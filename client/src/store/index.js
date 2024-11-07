import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import router from "../../router/index"; // Import router

export default createStore({
  state: {
    cart: [],
    currentPage: 1,
    totalPages: 0,
    totalProducts: 0, // Added state for total products
    isModalOpen: false, // State to control modal visibility
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
    totalProducts: (state) => state.totalProducts, // Getter for total products
    isModalOpen: (state) => state.isModalOpen, // Getter for modal state
    reviewData: (state) => state.review, // Getter for review data
    isAuthenticated: (state) => !!state.user,
    userInitials: (state) => {
      if (state.user && state.user.name) {
        // Split the name into parts and take the first letter of each part
        const nameParts = state.user.name.split(" ");
        const initials = nameParts.map((part) => part.charAt(0)).join("");
        return initials.toUpperCase();
      }
      return "";
    },
    userId: (state) => (state.user ? state.user.id : null), // Getter for user ID
    userRole: (state) => (state.user ? state.user.role : null), // Getter for user role
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
    SET_MODAL_OPEN(state, isOpen) {
      state.isModalOpen = isOpen;
    },
    SET_REVIEW_RATING(state, rating) {
      state.review.rating = rating;
    },
    RESET_REVIEW(state) {
      state.review = { name: "", rating: 0, title: "", content: "" };
    },
    SET_CURRENT_PAGE(state, page) {
      state.currentPage = page; // Mutation to update current page
    },
    SET_TOTAL_PAGES(state, totalPages) {
      state.totalPages = totalPages; // Mutation to update total pages
    },
    SET_TOTAL_PRODUCTS(state, totalProducts) {
      state.totalProducts = totalProducts; // Mutation to update total products
    },
    REMOVE_PRODUCT(state, productId) {
      // Mutation to remove a product from cart or store
      state.cart = state.cart.filter((product) => product.id !== productId);
      // Decrease total products by 1 after deletion
      state.totalProducts -= 1;
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
    openModal({ commit }) {
      commit("SET_MODAL_OPEN", true);
    },
    closeModal({ commit }) {
      commit("SET_MODAL_OPEN", false);
    },
    updateReviewRating({ commit }, rating) {
      commit("SET_REVIEW_RATING", rating);
    },
    resetReview({ commit }) {
      commit("RESET_REVIEW");
    },
    setCurrentPage({ commit }, page) {
      commit("SET_CURRENT_PAGE", page); // Action to update current page
    },
    setTotalPages({ commit }, totalPages) {
      commit("SET_TOTAL_PAGES", totalPages); // Action to update total pages
    },
    setTotalProducts({ commit }, totalProducts) {
      commit("SET_TOTAL_PRODUCTS", totalProducts); // Action to update total products
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
    async deleteProduct({ commit }, productId) {
      try {
        const response = await fetch(
          `${import.meta.env.VITE_API_URL}/products/${productId}`,
          {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        if (!response.ok) {
          throw new Error("Failed to delete the product");
        }

        // After deleting, remove the product from the store state and update the total products
        commit("REMOVE_PRODUCT", productId);
      } catch (error) {
        console.error("Error deleting product:", error);
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
        totalProducts: state.totalProducts, // Persist total products
        isModalOpen: state.isModalOpen,
        user: state.user, // Persist user state
        review: state.review, // Persist review state
      }),
    }),
  ],
});
