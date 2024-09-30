<template>
  <div v-if="isAdminOrProfessor" class="relative">
    <button @click="toggleMenu" class="text-gray-500 hover:text-gray-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01" />
      </svg>
    </button>
    <div v-if="isMenuOpen" :class="['absolute mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-10', 'menu-up']">
      <Link :href="`/users/${userId}/attendance`" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
        Voir la feuille de présence
      </Link>
      <Link v-if="isAdmin" :href="`/users/${userId}/edit`" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
        Modifier
      </Link>
      <button v-if="isAdmin" @click="showModal = true" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
        Supprimer
      </button>
    </div>
    <Modal :show="showModal" @close="showModal = false">
      <template #default>
        <div class="p-4">
          <h2 class="text-lg font-bold">Confirmer la suppression</h2>
          <p>Êtes-vous sûr de vouloir supprimer {{ userName }} ?</p>
          <div class="mt-4 flex justify-end">
            <button 
              @click="showModal = false" 
              class="mr-2 px-4 py-2 bg-gray-300 rounded"
            >
              Annuler
            </button>
            <button 
              @click="handleDeleteUser"
              class="px-4 py-2 bg-red-600 text-white rounded"
            >
              Supprimer
            </button>
          </div>
        </div>
      </template>
    </Modal>
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import { Link } from '@inertiajs/vue3';
  import Modal from './Modal.vue';

  const props = defineProps({
    userId: {
      type: Number,
      required: true
    },
    userName: {
      type: String,
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
  const showModal = ref(false);

  const toggleMenu = () => {
    menuOpen.value = !menuOpen.value;
  };

  const isMenuOpen = computed(() => menuOpen.value);
</script>

<style scoped>
  .menu-up {
    bottom: 100%;
    right: 0;
    margin-bottom: 0.5rem;
  }

  .menu-transition {
    transition: opacity 0.2s ease, transform 0.2s ease;
    opacity: 0;
    transform: translateY(-10px);
  }

  .menu-transition-enter-active {
      opacity: 1;
      transform: translateY(0);
  }
</style>