<script setup>
import { ref, onMounted, reactive, computed } from "vue";
import { RouterLink } from "vue-router";
import PrimaryButton from "@/components/PrimaryButton.vue";
import { ArrowLeft, ListCheck, Tags } from "lucide-vue-next";
import Inputfields from "@/components/Inputfields.vue";
import Layout from "@/components/Layout.vue";

import { storeToRefs } from "pinia";
import { useCategoryStore } from "@/stores/category";
import { useProductStore } from "@/stores/product";
import { useRoute } from "vue-router";

const route = useRoute();
const { getCategories } = useCategoryStore();
const { data } = storeToRefs(useCategoryStore());

const { getItem, updateProduct, deleteProduct } = useProductStore();
const { selectedItem, errors } = storeToRefs(useProductStore());

const productFormData = reactive({
  name: "",
  sell_price: 0,
  is_active: 0,
  category_ids: null,
});

const populateData = (data) => {
  productFormData.name = data.name || "";
  productFormData.sell_price = parseFloat(data.sell_price) || 0;
  productFormData.is_active = data.is_active === 1 ? 1 : 0;

  if (data.categories) {
    productFormData.category_ids = data.categories.map((c) => c.id);
  } else {
    productFormData.category_ids = [];
  }
};

const isCategoryExist = (id) => {
  return productFormData.category_ids?.includes(Number(id)) ?? false;
};

const toggleCategory = (categoryId) => {
  const id = Number(categoryId);

  if (!productFormData.category_ids) {
    productFormData.category_ids = [];
  }

  const index = productFormData.category_ids.indexOf(id);

  if (index === -1) {
    productFormData.category_ids.push(id);
  } else {
    productFormData.category_ids.splice(index, 1);
  }
  console.log(productFormData.category_ids);
};

onMounted(async () => {
  await getCategories("/categories");
  await getItem(`products/${route.params.id}`);
  populateData(selectedItem.value);
});
</script>

<template>
  <Layout>
    <RouterLink :to="{ name: 'home' }">
      <PrimaryButton buttonName="Back" class="btn-secondary">
        <template #left-icon>
          <ArrowLeft size="18" />
        </template>
      </PrimaryButton>
    </RouterLink>

    <div class="w-full h-full">
      <div class="w-full flex flex-col mt-8">
        <div class="w-full bg-gray-100 p-6 rounded-lg">
          <div class="flex items-center w-full justify-between mb-6">
            <h3 class="text-2xl font-semibold text-gray-700 ">
              Product <span class="text-primary">{{ selectedItem?.name }}</span>
            </h3>
            <button
              class="link link-error"
              @click="
                deleteProduct(`products/${route.params.id}`, selectedItem?.name)
              "
            >
              Delete this product
            </button>
          </div>

          <form
            @submit.prevent="
              () =>
                updateProduct(`products/${route.params.id}`, productFormData)
            "
          >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h3 class="text-lg font-medium mb-2">Information</h3>
                <Inputfields
                  v-model="productFormData.name"
                  inputLabel="Name"
                  inputType="text"
                  placeholder="Product name"
                  :error="errors.name?.[0]"
                />
                <Inputfields
                  v-model="productFormData.sell_price"
                  inputLabel="Sell Price"
                  inputType="number"
                  placeholder="Your selling price"
                  :error="errors.sell_price?.[0]"
                />
                <div class="mt-4">
                  <label class="label text-sm">Status</label>
                  <select
                    v-model="productFormData.is_active"
                    class="select w-full"
                  >
                    <option :value="0">Inactive</option>
                    <option :value="1">Active</option>
                  </select>
                </div>
              </div>

              <div>
                <h3 class="text-lg font-medium mb-2">Categories</h3>
                <div class="max-h-64 overflow-y-auto rounded-lg p-2 bg-white">
                  <div
                    v-for="category in data"
                    :key="category.id"
                    class="flex-shrink-0"
                  >
                    <label
                      class="flex items-center gap-2 px-2 py-1 hover:bg-blue-200 rounded"
                    >
                      <input
                        type="checkbox"
                        class="checkbox checkbox-primary"
                        :checked="isCategoryExist(category.id)"
                        @change="toggleCategory(category.id)"
                        :value="category.id"
                      />
                      <span class="whitespace-nowrap">{{ category.name }}</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-end items-center gap-2 mt-6">
              <PrimaryButton buttonName="Update Product">
                <template #right-icon>
                  <ListCheck size="18" />
                </template>
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </Layout>
</template>