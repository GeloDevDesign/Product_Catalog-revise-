import { defineStore } from "pinia";
import axios from "axios";
import { useModalAlert } from "@/composables/useModal";
import router from "@/router";

const { modalAlert } = useModalAlert();

export const useAuthStore = defineStore("authStore", {
    state: () => ({
        user: null,
        errors: {},
    }),

    getters: {},

    actions: {
        async getUser() {
            try {
                const response = await axios.get("/api/user");
                this.user = response.data;
            } catch (error) {
                this.user = null;
                modalAlert(
                    "Error",
                    "Session expired or not logged in.",
                    "error"
                );
            }
        },

        async authenticate(apiRoute, formData) {
            this.errors = {};

            try {
                await axios.get("/sanctum/csrf-cookie");

                const response = await axios.post(`/api/${apiRoute}`, formData);

                this.user = response.data.user;
                this.errors = {};
                modalAlert(
                    "Success",
                    `Welcome ${response.data.user.name}`,
                    "success"
                );
                router.push({ name: "home" });
            } catch (error) {
                if (error.response?.status === 429) {
                    modalAlert(
                        "Too many attempts",
                        "Please wait a moment.",
                        "error"
                    );
                    return;
                }

                this.errors = error.response?.data?.errors || {};
            }
        },

        async logout() {
            try {
               
                await axios.post("/api/logout");
                this.user = null;
                this.errors = {};
                modalAlert("Success", "Logged out successfully.", "success");
                router.push({ name: "login" });
            } catch (error) {
                modalAlert("Error", "An unexpected error occurred.", "error");
            }
        },
    },
});
