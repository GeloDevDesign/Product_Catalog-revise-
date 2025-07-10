<script setup>
import { ref, onMounted, watch } from "vue";
import { debounce } from "lodash";
import { storeToRefs } from "pinia";

import { useCategoryStore } from "@/stores/category";
import { useProductStore } from "@/stores/product";


const categoryStore = useCategoryStore();
const productStore = useProductStore();


const { data: categories } = storeToRefs(categoryStore);
const { pagination } = storeToRefs(productStore);


const searchValue = ref("");
const selectedCategory = ref("");


const debouncedFilter = debounce(() => {
  productStore.getProducts(
    "products",
    pagination.value?.current_page ?? 1,
    pagination.value?.per_page ?? 10,
    searchValue.value,
    selectedCategory.value
  );
}, 500);

watch([searchValue, selectedCategory], () => {
  debouncedFilter();
});


onMounted(async () => {
  await categoryStore.getCategories("categories");
});
</script>

<template>
  <div class="join">
    <input
      v-model="searchValue"
      class="input join-item"
      placeholder="Search"
    />
    <select v-model="selectedCategory" class="select join-item max-w-32">
      <option value="">All Categories</option>
      <option
        v-for="category in categories"
        :key="category.id"
        :value="category.id"
      >
        {{ category.name }}
      </option>
    </select>
  </div>
</template>
