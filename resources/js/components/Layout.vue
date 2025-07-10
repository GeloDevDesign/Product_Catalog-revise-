<script setup>
import { ref } from "vue";
import Header from "./Header.vue";
</script>

<template>
  <Header />
  <section class="py-6 px-10">
    <slot></slot>
  </section>
</template>