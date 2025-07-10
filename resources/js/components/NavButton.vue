<script setup>
import { RouterLink, RouterView } from "vue-router";

const props = defineProps({
  linkName: {
    type: String,
    default: "/",
  },
});
</script>

<template>
  <RouterLink :to="props.linkName" class="btn btn-primary"></RouterLink>
</template>