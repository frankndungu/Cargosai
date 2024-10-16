// store/reviewStore.js
const state = {
  review: {
    name: "",
    rating: 0,
    title: "",
    content: "",
  },
};

const getters = {
  reviewData: (state) => state.review,
};

const mutations = {
  SET_REVIEW_RATING(state, rating) {
    state.review.rating = rating;
  },
  RESET_REVIEW(state) {
    state.review = { name: "", rating: 0, title: "", content: "" };
  },
};

const actions = {
  updateReviewRating({ commit }, rating) {
    commit("SET_REVIEW_RATING", rating);
  },
  resetReview({ commit }) {
    commit("RESET_REVIEW");
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
