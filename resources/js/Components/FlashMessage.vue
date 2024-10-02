<template>
    <div 
        v-if="visible" 
        :class="flashMessageClass" 
        :style="{ transform: `translateX(${translateX})`, position: 'fixed', top: '20px', right: '20px', zIndex: '1000' }"
    >
        {{ flashMessage }}
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue';

    const props = defineProps({
        flashMessage: String,
        color: String,
    });

    const visible = ref(true);
    const translateX = ref('100%');

    const flashMessageClass = computed(() => {
        return `bg-${props.color}-500 text-white p-4 rounded mb-4 transition-transform duration-500`;
    });

    setTimeout(() => {
        translateX.value = '0'; 
        setTimeout(() => {
            translateX.value = '100%'; 
            setTimeout(() => {
                visible.value = false;
            }, 500); 
        }, 15000);
    }, 0);
</script>