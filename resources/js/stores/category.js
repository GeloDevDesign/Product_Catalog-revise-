import { defineStore } from "pinia";
import api from "@/lib/axios"; 
import { useModalAlert } from "@/composables/useModal";

const { modalAlert } = useModalAlert();

export const useCategoryStore = defineStore("categoryStore", {
    state: () => ({
        data: [],
        errors: {},
    }),

    actions: {
        async getCategories(apiRoute) {
            this.errors = {};

            try {
                const { data } = await api.get(`/${apiRoute}`);
                this.data = data || [];
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                modalAlert("Error", "Failed to fetch categories.", "error");
            }
        },
    },
});
