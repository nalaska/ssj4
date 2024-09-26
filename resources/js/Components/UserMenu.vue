<!-- resources/js/Components/UserMenu.vue -->
<template>
    <div v-if="isAdminOrProfessor" class="relative">
      <button @click="toggleMenu" class="text-gray-500 hover:text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01" />
        </svg>
      </button>
      <div v-if="isMenuOpen" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-10">
        <Link :href="`/users/${userId}/attendance`" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
          Voir la feuille de présence
        </Link>
        <Link v-if="isAdmin" :href="`/users/${userId}/edit`" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
          Modifier
        </Link>
        <button v-if="isAdmin" @click="handleDeleteUser" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
          Supprimer
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { Link } from '@inertiajs/vue3';
  
  const props = defineProps({
    userId: {
      type: Number,
      required: true
    },
    isAdmin: {
      type: Boolean,
      required: true
    },
    isAdminOrProfessor: {
      type: Boolean,
      required: true
    },
    handleDeleteUser: {
      type: Function,
      required: true
    }
  });
  
  const menuOpen = ref(false);
  
  const toggleMenu = () => {
    menuOpen.value = !menuOpen.value;
  };
  
  const isMenuOpen = computed(() => menuOpen.value);
  </script>