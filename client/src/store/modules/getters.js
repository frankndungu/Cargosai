export default {
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
};
