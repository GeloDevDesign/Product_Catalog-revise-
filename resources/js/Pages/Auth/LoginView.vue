<script setup>
import { reactive } from "vue";
import { NotebookPen } from "lucide-vue-next";
import { RouterLink } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";

import Inputfields from "@/components/Inputfields.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";

const { errors } = storeToRefs(useAuthStore());
const { authenticate } = useAuthStore();


const formData = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});
</script>

<template>
  <section class="flex justify-center items-center h-screen flex-col">
    <div class="flex items-center mb-6">
      <NotebookPen size="28" />
      <span class="text-base font-bold text-gray-700">Product Catalog</span>
    </div>
    <form
      @submit.prevent="
        () => {
          authenticate('login', formData);
        }
      "
      class="fieldset border-base-300 border-none rounded-box w-sm border p-4"
    >
     
      <Inputfields
        v-model="formData.email"
        inputLabel="Email"
        inputType="email"
        placeholder="Your email"
        :error="errors.email?.[0]"
      />
      <Inputfields
        v-model="formData.password"
        inputLabel="Password"
        inputType="password"
        placeholder="Your password"
        :error="errors.password?.[0]"
      />
     
      <PrimaryButton buttonName="Login" class="mt-4"/>
    </form>
    <div class="text-sm opacity-60 mt-4">
      Don't have an account ?
      <RouterLink :to="{ name: 'register' }">
        <span class="link link-primary">Register</span></RouterLink
      >
    </div>
  </section>
</template>