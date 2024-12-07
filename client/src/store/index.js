import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import router from "../../router/index";

export default createStore({
  state: {
    cart: [],
    currentPage: 1,
    totalPages: 0,
    totalProducts: 0,
    isModalOpen: false,
    review: {
      name: "",
      rating: 0,
      title: "",
      content: "",
    },
    user: null,
    product: {
      name: "",
      stock: "",
      price: "",
      vendor: "",
      vendorEmail: "",
      vendorLocation: "",
      material: "",
      dimensions: "",
      weight: "",
      description: "",
      images: [], // Store base64 strings for uploaded images
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
    totalProducts: (state) => state.totalProducts,
    isModalOpen: (state) => state.isModalOpen,
    reviewData: (state) => state.review,
    isAuthenticated: (state) => !!state.user,
    userInitials: (state) => {
      if (state.user && state.user.name) {
        const nameParts = state.user.name.split(" ");
        const initials = nameParts.map((part) => part.charAt(0)).join("");
        return initials.toUpperCase();
      }
      return "";
    },
    userId: (state) => (state.user ? state.user.id : null),
    userRole: (state) => (state.user ? state.user.role : null),
    product: (state) => state.product,
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
      state.currentPage = page;
    },
    SET_TOTAL_PAGES(state, totalPages) {
      state.totalPages = totalPages;
    },
    SET_TOTAL_PRODUCTS(state, totalProducts) {
      state.totalProducts = totalProducts;
    },
    REMOVE_PRODUCT(state, productId) {
      state.cart = state.cart.filter((product) => product.id !== productId);
      state.totalProducts -= 1;
    },
    SET_PRODUCT(state, product) {
      state.product = product;
    },
    SET_MAIN_IMAGE(state, imageBase64) {
      state.product.mainImage = imageBase64;
    },
    ADD_IMAGE(state, imageBase64) {
      state.product.images.push(imageBase64); // Store the base64 image
    },
    REMOVE_MAIN_IMAGE(state) {
      state.product.mainImage = null; // Clear the main image
    },
    REMOVE_IMAGE(state, imageIndex) {
      state.product.images.splice(imageIndex, 1);
    },
  },
  actions: {
    async addToCart({ commit }, product) {
      commit("ADD_TO_CART", product);
    },
    async removeFromCart({ commit }, id) {
      commit("REMOVE_FROM_CART", id);
    },
    async increaseItemQuantity({ commit }, id) {
      commit("INCREASE_ITEM_QUANTITY", id);
    },
    async decreaseItemQuantity({ commit }, id) {
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
      commit("SET_CURRENT_PAGE", page);
    },
    setTotalPages({ commit }, totalPages) {
      commit("SET_TOTAL_PAGES", totalPages);
    },
    setTotalProducts({ commit }, totalProducts) {
      commit("SET_TOTAL_PRODUCTS", totalProducts);
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

        if (!response.ok) throw new Error("Failed to fetch user data.");

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

        if (!response.ok) throw new Error("Failed to delete the product");

        commit("REMOVE_PRODUCT", productId);
      } catch (error) {
        console.error("Error deleting product:", error);
      }
    },
    async uploadImage({ commit }, file) {
      // Convert the file to a base64 string
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        commit("ADD_IMAGE", reader.result); // Add base64 image to state
      };
      reader.onerror = (error) =>
        console.error("Error converting file:", error);
    },
    async removeImage({ commit }, imageIndex) {
      commit("REMOVE_IMAGE", imageIndex);
    },
  },
  plugins: [
    createPersistedState({
      storage: window.localStorage,
      reducer: (state) => ({
        cart: state.cart,
        currentPage: state.currentPage,
        totalPages: state.totalPages,
        totalProducts: state.totalProducts,
        isModalOpen: state.isModalOpen,
        user: state.user,
        review: state.review,
        product: state.product, // Persist product including images as base64
      }),
    }),
  ],
});
