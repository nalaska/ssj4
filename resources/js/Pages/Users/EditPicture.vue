<template>
    <AuthenticatedLayout>
        <Breadcrumb :items="breadcrumbItems" />
        <div class="flex justify-center items-center min-h-screen bg-gray-100">
            <div class="w-full max-w-md p-4">
                <h1 class="text-2xl font-bold mb-4 text-center">Modifier la photo de l'adhérent</h1>
                <form
                    @submit.prevent="submit"
                    enctype="multipart/form-data"
                    class="bg-white shadow-md rounded-lg p-6"
                >
                    <div class="mb-4">
                        <label for="picture" class="block text-gray-700 text-sm font-bold mb-2">Photo</label>
                        <input 
                            type="file"
                            @change="handleFileChange"
                            id="picture"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        />
                        <InputError :message="form.errors.picture" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    import InputError from '@/Components/InputError.vue';
    import { defineProps, ref } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Breadcrumb from '@/Components/Breadcrumb.vue';

    const props = defineProps({
        user: Object,
    });

    const breadcrumbItems = ref([
        { text: 'Utilisateurs', href: '/users' },
        { text: 'Modifier', href: `/users/${props.user.id}/edit` },
        { text: 'Modifier la photo' }
    ]);

    const form = useForm({
        picture: null,
    });

    function submit() {
        form.post(`/users/${props.user.id}/update-picture`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        });
    }

    function handleFileChange(event) {
        form.picture = event.target.files[0];
    }
</script>