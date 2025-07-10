<script setup>
import { ref } from "vue";

const props = defineProps({
  buttonName: {
    type: String,
    default: "No button name",
  },
});
</script>

<template>
  <button class="btn btn-primary ">
    <span class="flex items-center justify-center gap-2">
      <span class="left-icon">
        <slot name="left-icon"></slot>
      </span>
      {{ props.buttonName }}
      <span class="right-icon">
        <slot name="right-icon"></slot>
      </span>
    </span>
  </button>
</template>