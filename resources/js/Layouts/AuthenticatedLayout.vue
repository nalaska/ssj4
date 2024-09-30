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


    const logout = async () => {
        const response = await fetch(route('logout.google'), {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        });

        if (response.ok) {
            window.location.href = '/'; 
        } else {
            console.error('Erreur lors de la déconnexion');
        }
    };
</script>