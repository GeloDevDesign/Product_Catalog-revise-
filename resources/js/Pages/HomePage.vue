<script setup>
import { reactive, onMounted } from "vue";
import Layout from "@/components/Layout.vue";
import Pagination from "@/components/Pagination.vue";
import { SquarePen, Plus } from "lucide-vue-next";
import { RouterLink } from "vue-router";
import { storeToRefs } from "pinia";
import { useProductStore } from "@/stores/product";
import PrimaryButton from "@/components/PrimaryButton.vue";
import FilterSearch from "@/components/FilterSearch.vue";

const productStore = useProductStore();
const { getProducts } = productStore;
const { data, pagination, loading } = storeToRefs(productStore);

const handlePageChange = (page) => {
  getProducts("products", page, 10);
};

onMounted(async () => {
  await getProducts("products", 1, 10);
});
</script>

<template>
  <Layout>
    <div class="flex w-full justify-between mb-2">
      <div>
        <h2 class="text-xl font-semibold">Your Product List</h2>
      </div>
      <div class="flex gap-2">
        <FilterSearch />
        <a onclick="window.location.href = '/product/create'" class="btn btn-error">Add new product (Laravel)</a>

        <RouterLink :to="{ name: 'add-product' }">
          <PrimaryButton buttonName="Add new product">
            <template #right-icon>
              <Plus />
            </template>
          </PrimaryButton>
        </RouterLink>
      </div>
    </div>
    <div v-if="loading" class="text-center py-8">
      <div class="loading loading-spinner loading-lg"></div>
      <p class="mt-2">Loading products...</p>
    </div>

    <div
      v-else
      class="overflow-x-auto rounded-box border border-base-content/5 bg-white min-h-96"
    >
      <table class="table table-pin-rows">
        <thead class="bg-base-content">
          <tr class="bg-base-content text-white">
            <th></th>
            <th>Name</th>
            <th>Sell Price</th>
            <th>Status</th>
            <th>Category</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!data || data.length === 0">
            <td colspan="6" class="text-center py-8 text-gray-500">
              No products found
            </td>
          </tr>

          <tr v-for="(item, index) in data" :key="item.id">
            <td>
              {{
                pagination
                  ? (pagination.current_page - 1) * pagination.per_page +
                    index +
                    1
                  : index + 1
              }}
            </td>
            <td class="font-semibold">{{ item.name }}</td>
            <td class="opacity-90">P{{ item.sell_price }}</td>
            <td>
              <div
                class="badge badge-soft rounded-full"
                :class="
                  item.is_active ? 'badge-primary font-medium' : 'badge-neutral'
                "
              >
                {{ item.is_active ? "Active" : "Inactive" }}
              </div>
            </td>
            <td>
              <span class="text-sm">{{
                item.categories?.map((c) => c.name).join(", ") ||
                "No categories"
              }}</span>
            </td>
            <td>
              <RouterLink :to="{ name: 'product', params: { id: item.id } }">
                <div class="tooltip" data-tip="Edit">
                  <button class="btn btn-secondary btn-sm">
                    <SquarePen size="18" />
                  </button>
                </div>
              </RouterLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Pagination
      v-if="pagination && pagination.total > 0"
      :pagination="pagination"
      @page-changed="handlePageChange"
      class="mt-4"
    />
  </Layout>
</template>