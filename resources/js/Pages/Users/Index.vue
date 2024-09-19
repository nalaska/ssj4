<template>
  <div class="container mx-auto p-4">
      <h1 class="text-3xl font-bold mb-6 text-center">Adhérents</h1>

      <flashMessage 
        v-if="flashMessageError || flashMessageSuccess" 
        :flashMessage="flashMessageError || flashMessageSuccess" 
        :color="flashMessageError ? 'red' : 'green'" 
      ></flashMessage>

      <div class="flex justify-end mb-4">
          <Link href="/users/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
              Ajouter un utilisateur
          </Link>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <div
              v-for="user in paginatedUsers"
              :key="user.id"
              :class="['shadow-lg rounded-lg p-6 relative', getBeltClass(user.belt)]"
          >
              <div class="flex justify-between items-center">
                  <h2 class="text-xl font-semibold mb-2">{{ user.name }}</h2>
                  <div class="relative">
                      <button @click="toggleMenu(user.id)" class="text-gray-500 hover:text-gray-700">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01" />
                          </svg>
                      </button>
                      <div v-if="isMenuOpen(user.id)" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                          <Link 
                              :href="`/users/${user.id}/attendance`"
                              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                          >
                              Voir la feuille de présence
                          </Link>
                          <Link 
                              :href="`/users/${user.id}/edit`"
                              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                          >
                              Modifier
                          </Link>
                          <button 
                              @click="handleDeleteUser(user.id)"
                              class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                          >
                              Supprimer
                          </button>
                      </div>
                  </div>
              </div>
              <p class="mb-1"><strong>Email:</strong> {{ user.email }}</p>
              <p class="mb-1"><strong>Ceinture:</strong> {{ formatBelt(user.belt) }}</p>
              <p class="mb-1"><strong>Téléphone:</strong> {{ user.phone }}</p>
              <p class="mb-1"><strong>Année d'inscription:</strong> {{ user.year_of_registration }}</p>
              <p class="mb-1"><strong>Status:</strong> {{ user.status }}</p>
              <p class="mb-1"><strong>Âge:</strong> {{ calculateAge(user.date_of_birth) }} ans</p>
              <div v-if="user.picture" class="mb-4">
                <img :src="`/storage/${user.picture}`" class="w-24 h-auto rounded" />
              </div>
          </div>
      </div>
  </div>
  <div class="mt-6 mb-10">
      <Pagination :links="paginationLinks" @page-changed="changePage" />
  </div>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import { Link, usePage } from '@inertiajs/vue3';
  import { defineProps } from 'vue';
  import { formatBelt } from '@/utils/utils';
  import { calculateAge } from '@/utils/usersFunctions';

  import Pagination from '@/Components/Pagination.vue';
  import flashMessage from '@/Components/FlashMessage.vue';

  const props = defineProps({
      users: {
          type: Array,
          required: true
      }
  });

  const flashMessageError = computed(() => usePage().props.flash.error);
  const flashMessageSuccess = computed(() => usePage().props.flash.success);

  const menuOpen = ref(null);
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const currentPage = ref(1);
  const perPage = 12;
  const localUsers = ref([...props.users]);

  const paginatedUsers = computed(() => {
      const start = (currentPage.value - 1) * perPage;
      const end = start + perPage;
      return localUsers.value.slice(start, end);
  });

  const totalPages = computed(() => Math.ceil(localUsers.value.length / perPage));

  const paginationLinks = computed(() => {
      const links = [];
      for (let i = 1; i <= totalPages.value; i++) {
          links.push({
              url: i,
              label: i,
              active: i === currentPage.value
          });
      }
      return links;
  });

  const changePage = (page) => {
      currentPage.value = page;
  };

  const beltStyles = {
      blanche: { bg: 'bg-white', text: 'text-black' },
      grise: { bg: 'bg-gray-400', text: 'text-white' },
      jaune: { bg: 'bg-yellow-400', text: 'text-black' },
      orange: { bg: 'bg-orange-400', text: 'text-black' },
      verte: { bg: 'bg-green-400', text: 'text-white' },
      bleu: { bg: 'bg-blue-400', text: 'text-white' },
      violette: { bg: 'bg-purple-400', text: 'text-white' },
      marron: { bg: 'bg-brown-400', text: 'text-white' },
      noire: { bg: 'bg-black', text: 'text-white' },
      rouge_noire: { bg: 'bg-black', text: 'text-white' },
      rouge_blanche: { bg: 'bg-black', text: 'text-white' },
      rouge: { bg: 'bg-red-600', text: 'text-white' },
      default: { bg: 'bg-gray-200', text: 'text-black' },
  };

  const getBeltClass = (belt) => {
      const style = beltStyles[belt] || beltStyles.default;
      return `${style.bg} ${style.text}`;
  };

  async function handleDeleteUser(userId) {
      await deleteUser(userId, csrfToken);
      localUsers.value = localUsers.value.filter(user => user.id !== userId);
  }

  const toggleMenu = (userId) => {
      menuOpen.value = menuOpen.value === userId ? null : userId;
  };

  const isMenuOpen = (userId) => {
      return menuOpen.value === userId;
  };
  async function deleteUser(userId, csrfToken)
  {
    if (!confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
        return;
    }

    try {
      await fetch(`/users/${userId}`, {
          method: 'DELETE',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
          }
      });
    } catch (error) {
          console.error('Erreur lors de la suppression de l\'utilisateur', error);
    }
  }
</script>