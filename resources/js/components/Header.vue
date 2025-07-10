<script setup>
import { NotebookPen } from "lucide-vue-next";
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";
const { user } = storeToRefs(useAuthStore());
const { authenticate,logout } = useAuthStore();
</script>

<template>
  <div class="navbar bg-base-content shadow-sm px-8">
    <div class="flex-1 ">
      <div class="flex items-center gap-2">
        <NotebookPen color="white" />
        <p class="text-sm font-bold text-gray-300">Product Catalog</p>
      </div>
    </div>
    <div class="flex-none">
      <div class="dropdown dropdown-end">
        <div class="mr-2">
          <span class="text-sm opacity=75 text-white">Welcome Back! </span>
          <span v-if="user?.name" class="text-sm font-semibold text-accent">{{ user.name || 'No username' }}</span>
        </div>
      </div>
      <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
          <div class="w-10 rounded-full">
            <img
              alt="Tailwind CSS Navbar component"
              src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
            />
          </div>
        </div>
        <ul
          tabindex="0"
          class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow"
        >
          <li><a class="text-content">Profile</a></li>
          <li><a class="text-content" @click="logout">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</template>