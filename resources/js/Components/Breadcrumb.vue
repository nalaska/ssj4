<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li 
                v-for="(item, index) in items" 
                :key="index" 
                class="flex items-center"
            >
                <font-awesome-icon 
                    :icon="faArrowRight" 
                    v-if="index !== 0"
                    class="mr-2"  
                />
                <Link 
                    v-if="item.href" 
                    :href="item.href" 
                    class="text-gray-700 hover:text-blue-600 font-semibold"
                >
                    {{ item.text }}
                </Link>
                <p 
                    v-else 
                    class="text-gray-700 hover:text-blue-600 font-semibold"
                >
                    {{ item.text }}
                </p>
            </li>
        </ol>
    </nav>
</template>

<script setup>
    import { defineProps } from 'vue';
    import { Link } from '@inertiajs/vue3';
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    import { faArrowRight } from '@fortawesome/free-solid-svg-icons';

    const props = defineProps({
        items: {
            type: Array,
            default: () => []
        }
    });
</script>

