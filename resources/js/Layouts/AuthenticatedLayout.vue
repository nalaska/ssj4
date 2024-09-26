<template>
    <div>
        <div class="flex justify-end p-4">
            <form @submit.prevent="logout">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                    Déconnexion
                </button>
            </form>
        </div>
        <div class="min-h-screen bg-gray-100">
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>

    import { useStore } from 'vuex';

    const store = useStore();

    const logout = async () => {
        const response = await fetch(route('logout.google'), {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${store.getters.getToken}`
            },
        });

        if (response.ok) {
            store.dispatch('removeToken');
            window.location.href = '/'; 
        } else {
            console.error('Erreur lors de la déconnexion');
        }
    };
</script>