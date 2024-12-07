import { createStore } from "vuex";
import state from "./state";
import getters from "./getters";
import mutations from "./mutations";
import actions from "./actions";
import plugins from "./plugins";
import cart from "./cart";
export default createStore({
  state,
  getters,
  mutations,
  actions,
  plugins,
  modules: {
    cart,
  },
});
