<template>
    <div class="container mx-auto p-4">
      <h1 class="text-3xl font-bold mb-6 text-center">Adhérents</h1>
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
          <h2 class="text-xl font-semibold mb-2">{{ user.name }}</h2>
          <p class="mb-1"><strong>Email:</strong> {{ user.email }}</p>
          <p class="mb-1"><strong>Ceinture:</strong> {{ formatBelt(user.belt) }}</p>
          <p class="mb-1"><strong>Téléphone:</strong> {{ user.phone }}</p>
          <p class="mb-1"><strong>Année d'inscription:</strong> {{ user.year_of_registration }}</p>
          <p class="mb-1"><strong>Status:</strong> {{ user.status }}</p>
          <p class="mb-1"><strong>Âge:</strong> {{ calculateAge(user.date_of_birth) }} ans</p>
          <div v-if="user.picture" class="mb-4">
            <img :src="`/storage/${user.picture}`" class="w-full h-auto rounded" />
          </div>
          <div class="mt-4 flex justify-center space-x-2">
            <Link :href="`/users/${user.id}/edit`" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
              Modifier
            </Link>
            <button @click="handleDeleteUser(user.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
              Supprimer
            </button>
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
    import { Link } from '@inertiajs/vue3';
    import { defineProps } from 'vue';
    import { formatBelt, deleteUser } from '@/utils';
    import Pagination from '@/Components/Pagination.vue'

    const props = defineProps({
        users: {
            type: Array,
            required: true
        }
    });

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const currentPage = ref(1);
    const perPage = 12;

    // Créez une copie locale des utilisateurs
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

    function calculateAge(dateOfBirth) {
        const birthDate = new Date(dateOfBirth);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    async function handleDeleteUser(userId) {
        await deleteUser(userId, csrfToken);
        localUsers.value = localUsers.value.filter(user => user.id !== userId);
    }
</script>