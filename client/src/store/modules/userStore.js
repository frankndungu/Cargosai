// store/userStore.js
import router from "../../../router/index"; // Import router

const state = {
  user: null,
};

const getters = {
  isAuthenticated: (state) => !!state.user,
  userFirstName: (state) => {
    if (state.user && state.user.name) {
      return state.user.name.split(" ")[0];
    }
    return "";
  },
};

const mutations = {
  SET_USER(state, user) {
    state.user = user;
  },
  LOGOUT_USER(state) {
    state.user = null;
  },
};

const actions = {
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
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
