<script setup>
import { RouterLink } from "vue-router";
import PrimaryButton from "@/components/PrimaryButton.vue";
import { ArrowLeft } from "lucide-vue-next";

const props = defineProps({
  errorStatus: {
    type: Number,
    default: 404
  },
  errorMessage: {
    type: String,
    default: "Ooops.. this page not found."
  }
});

const errorTitles = {
  403: "403 Forbidden",
  404: "404 Not Found",
  500: "500 Server Error"
};

const defaultMessages = {
  403: "You don't have permission to access this resource.",
  404: "The page you're looking for doesn't exist.",
  500: "Something went wrong on our server."
};
</script>

<template>
  <section
    class="h-screen w-full flex justify-center items-center flex-col gap-8"
  >
    <div class="text-center gap-4">
      <h5 class="text-4xl font-bold">{{ errorTitles[errorStatus] || errorTitles[404] }}</h5>
     
    </div>
    <RouterLink :to="{ name: 'home' }">
      <PrimaryButton buttonName="Back to Dashboard">
        <template #left-icon>
          <ArrowLeft size="18" />
        </template>
      </PrimaryButton>
    </RouterLink>
  </section>
</template>