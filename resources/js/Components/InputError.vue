<template>
    <p v-if="message" class="text-red-500 text-xs italic">{{ message }}</p>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
    message: String
});
</script>