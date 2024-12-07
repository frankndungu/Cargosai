import router from "../../../router/index.js";

export default {
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
    reader.onerror = (error) => console.error("Error converting file:", error);
  },
  async removeImage({ commit }, imageIndex) {
    commit("REMOVE_IMAGE", imageIndex);
  },
};
