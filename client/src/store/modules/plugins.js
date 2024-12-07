import createPersistedState from "vuex-persistedstate";

export default [
  createPersistedState({
    storage: window.localStorage,
    reducer: (state) => ({
      currentPage: state.currentPage,
      totalPages: state.totalPages,
      totalProducts: state.totalProducts,
      isModalOpen: state.isModalOpen,
      user: state.user,
      review: state.review,
      product: state.product, // Persist product including images as base64
    }),
  }),
];
