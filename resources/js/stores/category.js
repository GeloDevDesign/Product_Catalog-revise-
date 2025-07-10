import { defineStore } from "pinia";
import { useModalAlert } from "@/composables/useModal";
const { modalAlert } = useModalAlert();

export const useCategoryStore = defineStore("categoryStore", {
  state: () => {
    return {
      data: {},
    };
  },
  getters: () => {},
  actions: {
    async getCategories(apiRoute) {
      this.errors = {};
      try {
        const response = await fetch(`/api/${apiRoute}`, {
          method: "GET",
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        const data = await response.json();
        this.data = data
       

      } catch (error) {
        modalAlert("Error", "An unexpected  while getting product data", "error");
      }
    },
  },
});