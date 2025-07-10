import { defineStore } from "pinia";
import axios from "axios";
import { useModalAlert } from "@/composables/useModal";
import { useToastAlert } from "@/composables/useToast";
import Swal from "sweetalert2";
import "sweetalert2/src/sweetalert2.scss";
import router from "@/router";

const { modalAlert } = useModalAlert();
const { toastAlert } = useToastAlert();

export const useProductStore = defineStore("productStore", {
  state: () => ({
    data: {},
    selectedItem: null,
    errors: {},
    pagination: null,
    loading: false,
  }),

  getters: {},

  actions: {
    
    async getProducts(apiRoute, page = 1, perPage = 10, search = "", filter = []) {
      this.loading = true;
      this.errors = {};

      try {
        const response = await axios.get(`/api/${apiRoute}`, {
          params: {
            page,
            per_page: perPage,
            search,
            category_id: filter,
          },
        });

        this.data = response.data.data;
        this.pagination = response.data;

      } catch (error) {
        modalAlert("Error", "An unexpected error occurred while fetching products.", "error");
        this.data = {};
      } finally {
        this.loading = false;
      }
    },

    
    async getItem(apiRoute) {
      this.errors = {};

      try {
        const response = await axios.get(`/api/${apiRoute}`);
        this.selectedItem = response.data;

      } catch (error) {
        router.push({
          name: "error",
          query: {
            status: error.response?.status || 500,
            message: error.response?.data?.message || "An unexpected error occurred.",
          },
        });
      }
    },

    
    async addProduct(apiRoute, formData) {
      this.errors = {};

      try {
        const response = await axios.post(`/api/${apiRoute}`, formData);

        toastAlert(response.data.message || "Product added successfully", "success");
        this.router.push({ name: "home" });

      } catch (error) {
        this.errors = error.response?.data?.errors || {};
        toastAlert("Failed to add product", "error");
      }
    },

   
    async updateProduct(apiRoute, formData) {
      this.errors = {};

      try {
        const response = await axios.patch(`/api/${apiRoute}`, formData);

        toastAlert(response.data.message || "Product updated successfully", "success");
        this.router.push({ name: "home" });

      } catch (error) {
        this.errors = error.response?.data?.errors || {};
        toastAlert("Failed to update product", "error");
      }
    },

   
    async deleteProduct(apiRoute, itemName) {
      this.errors = {};

      const result = await Swal.fire({
        title: `Are you sure you want to delete ${itemName}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
      });

      if (result.isConfirmed) {
        try {
          const response = await axios.delete(`/api/${apiRoute}`);

          toastAlert(response.data.message || "Product deleted successfully", "success");
          this.router.push({ name: "home" });

        } catch (error) {
          this.errors = error.response?.data?.errors || {};
          toastAlert("Failed to delete product", "error");
        }
      }
    },
  },
});
