<template>
    <div class="flex justify-center">
        <nav>
            <ul class="pagination flex space-x-2">
                <li v-for="link in links" :key="link.url || link.label" :class="['page-item', { 'active': link.active }]">
                    <a
                        v-if="link.url"
                        href="#"
                        class="page-link py-2 px-3 rounded border"
                        @click.prevent="changePage(link.url)"
                    >
                        {{ link.label }}
                    </a>
                    <span v-else class="page-link py-2 px-3 rounded border">{{ link.label }}</span>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
    links: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['page-changed']);

const changePage = (page) => {
    emit('page-changed', page);
};
</script>

<style scoped>
.page-item.active .page-link {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}
</style>