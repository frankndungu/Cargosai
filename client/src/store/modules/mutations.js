export default {
  SET_USER(state, user) {
    state.user = user;
  },
  LOGOUT_USER(state) {
    state.user = null;
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
};
