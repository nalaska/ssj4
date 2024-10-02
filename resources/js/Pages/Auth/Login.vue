<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <flashMessage 
            v-if="flashMessageError" 
            :flashMessage="flashMessageError" 
            :color="'red'" 
        />
        <div class="w-full max-w-md p-4">
            <div class="bg-white shadow-md rounded-lg p-6">
                <button 
                    @click="redirectToGoogle"
                    class="google-login-button"
                >
                    <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo" class="google-logo">
                    Se connecter avec Google
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import flashMessage from '@/Components/FlashMessage.vue';

    const flashMessageError = computed(() => usePage().props.flash.error);

    console.log(flashMessageError.value);
    const redirectToGoogle = () => {
        window.location.href = '/auth/google';
    }
   
</script>

<style>
    .google-login-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background-color: white;
        border: 1px solid #dcdcdc;
        border-radius: 4px;
        padding: 10px;
        font-weight: bold;
        color: #757575;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .google-login-button:hover {
        background-color: #f7f7f7;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .google-logo {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }
</style>