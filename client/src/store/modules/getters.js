export default {
  cartItems: (state) => state.cart,
  cartItemCount(state) {
    // Guard clause: ensure state.cart is an array before using reduce
    if (!Array.isArray(state.cart)) {
      return 0; // Return 0 if cart is not an array
    }

    return state.cart.reduce((total, item) => total + item.quantity, 0);
  },
  cartTotalPrice: (state) => {
    const total = state.cart.reduce(
      (total, item) =>
        total + (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0),
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
};
