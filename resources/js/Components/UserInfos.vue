<template>
    <div>
      <p class="mb-1"><strong>Email:</strong> {{ user.email }}</p>
      <p class="mb-1"><strong>Ceinture:</strong> {{ formatBelt(user.belt) }}</p>
      <p class="mb-1"><strong>Téléphone:</strong> {{ user.phone }}</p>
      <p class="mb-1"><strong>Année d'inscription:</strong> {{ user.year_of_registration }}</p>
      <p class="mb-1"><strong>Status:</strong> {{ user.status }}</p>
      <p class="mb-1"><strong>Âge:</strong> {{ calculateAge(user.date_of_birth) }} ans</p>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  import { formatBelt, calculateAge } from '@/utils/usersFunctions';
  
  const props = defineProps({
    user: {
      type: Object,
      required: true
    }
  });
  </script>