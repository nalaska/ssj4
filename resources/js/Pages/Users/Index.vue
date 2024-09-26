<template>
  <AuthenticatedLayout>
    <div class="container mx-auto p-4">
      <h1 class="text-3xl font-bold mb-6 text-center">Adhérents</h1>

      <flashMessage 
        v-if="flashMessageError || flashMessageSuccess" 
        :flashMessage="flashMessageError || flashMessageSuccess" 
        :color="flashMessageError ? 'red' : 'green'" 
      />

      <div class="flex justify-end mb-4">
        <Link 
          v-if="isAdmin" 
          href="/users/create" 
          class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300 ease-in-out transform hover:scale-105"
        >
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
            <UserMenu 
              :userId="user.id" 
              :isAdmin="isAdmin" 
              :isAdminOrProfessor="isAdminOrProfessor" 
              :handleDeleteUser="() => handleDeleteUser(user.id)" 
            />
          </div>
          <UserInfo :user="user" />
        </div>
      </div>
    </div>
    <div class="mt-6 mb-10">
      <Pagination :links="paginationLinks" @page-changed="changePage" />
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
  import { ref, computed } from 'vue';
  import { Link, usePage } from '@inertiajs/vue3';
  import { defineProps } from 'vue';
  import Pagination from '@/Components/Pagination.vue';
  import flashMessage from '@/Components/FlashMessage.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import UserInfo from '@/Components/UserInfos.vue';
  import UserMenu from '@/Components/UserMenu.vue';

  const props = defineProps({
      users: {
          type: Array,
          required: true
      }
  });

  const userRoles = ref(usePage().props.auth?.roles || []);
  const isAdmin = computed(() => userRoles.value.includes('administrateur'));
  const isAdminOrProfessor = computed(() => userRoles.value.includes('administrateur') || userRoles.value.includes('professeur'));
  const flashMessageError = computed(() => usePage().props.flash.error);
  const flashMessageSuccess = computed(() => usePage().props.flash.success);
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
          'X-CSRF-TOKEN': csrfToken,
          'Authorization': `Bearer ${store.getters.getToken}`
        }
      });
    } catch (error) {
          console.error('Erreur lors de la suppression de l\'utilisateur', error);
    }
  }
</script>