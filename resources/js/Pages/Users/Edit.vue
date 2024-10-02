<template>
    <AuthenticatedLayout>
        <FlashMessage 
            v-if="flashMessageError || flashMessageSuccess" 
            :flashMessage="flashMessageError || flashMessageSuccess" 
            :color="flashMessageError ? 'red' : 'green'" 
        />
        <Breadcrumb :items="breadcrumbItems" />
        <div class="flex justify-center items-center min-h-screen bg-gray-100">
            <div class="w-full max-w-md p-4">
                <h1 class="text-2xl font-bold mb-4 text-center">Modifier l'adhérent</h1>
                <form
                    @submit.prevent="submit"
                    class="bg-white shadow-md rounded-lg p-6"
                >
                    <div class="mb-4">
                        <label 
                            for="name" 
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                        Nom
                        </label>
                        <input 
                            type="text" 
                            v-model="form.name" 
                            id="name" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input 
                            type="email" 
                            v-model="form.email" 
                            id="email" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <label for="roles" class="block text-gray-700 text-sm font-bold mb-2">Rôle(s)</label>
                        <select
                            v-model="form.roles"
                            id="roles"
                            multiple
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                            <option
                                v-for="(translation, roleKey) in roles"
                                :key="roleKey"
                                :value="roleKey"
                            >
                                {{ translation }}
                            </option>
                        </select>
                        <InputError :message="form.errors.roles" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <label for="belt" class="block text-gray-700 text-sm font-bold mb-2">Ceinture</label>
                        <select 
                            v-model="form.belt" 
                            id="belt" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                            <option 
                                v-for="belt in belts" 
                                :key="belt" 
                                :value="belt"
                            >
                                {{ formatBelt(belt) }}
                            </option>
                        </select>
                        <InputError :message="form.errors.belt" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Téléphone</label>
                        <input 
                            type="text" 
                            v-model="form.phone" 
                            id="phone" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                        <InputError :message="form.errors.phone" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <label for="year_of_registration" class="block text-gray-700 text-sm font-bold mb-2">Année d'inscription</label>
                        <input 
                            type="number"
                            v-model="form.year_of_registration"
                            id="year_of_registration"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                        <InputError :message="form.errors.year_of_registration" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <input 
                            type="text"
                            v-model="form.status"
                            id="status" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                        <InputError :message="form.errors.status" class="mt-2" />
                    </div>
                    
                    <div class="mb-4">
                        <label for="date_of_birth" class="block text-gray-700 text-sm font-bold mb-2">Date de naissance</label>
                        <input 
                            type="date" 
                            v-model="form.date_of_birth" 
                            id="date_of_birth" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                        <InputError :message="form.errors.date_of_birth" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Mettre à jour</button>
                        <Link :href="`/users/${form.id}/edit-picture`" class="text-blue-500 hover:underline">Modifier la photo</Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
    import { useForm, Link, usePage } from '@inertiajs/vue3';
    import InputError from '@/Components/InputError.vue';
    import { defineProps, ref, computed } from 'vue';
    import { belts, roles } from '@/utils/utils';
    import { formatBelt } from '@/utils/usersFunctions';
    import { watch } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Breadcrumb from '@/Components/Breadcrumb.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

    const props = defineProps({
        user: Object,
        roles: Array
    });

    const breadcrumbItems = ref([
        { text: 'Utilisateurs', href: '/users' },
        { text: 'Modifier' }
    ]);

    const form = useForm({
        id: props.user?.id || '',
        name: props.user?.name || '',
        email: props.user?.email || '',
        belt: props.user?.belt || '',
        phone: props.user?.phone || '',
        year_of_registration: props.user?.year_of_registration || '',
        date_of_birth: props.user?.date_of_birth || '',
        status: props.user?.status || '',
        roles: props.roles || [],
        attendance: props.user?.attendance || {}
    });

    const flashMessageError = computed(() => usePage().props.flash.error);
    const flashMessageSuccess = computed(() => usePage().props.flash.success);

    watch(() => form.roles, (newRoles) => {
        form.roles = newRoles;
    });

    function submit() {
        form.put(`/users/${props.user.id}`);
    }

    function handleFileChange(event) {
        form.picture = event.target.files[0];
    }
</script>