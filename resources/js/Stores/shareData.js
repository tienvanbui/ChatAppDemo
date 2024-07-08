import { usePage } from "@inertiajs/vue3";
import { defineStore } from "pinia";

export const useShareDataStore = defineStore("shareData", {
  state: () => ({
    currentUser: usePage().props.auth.user,
  }),
  actions: {
    setCurrentUser(data) {
      this.currentUser = data;
    },
  },
});
