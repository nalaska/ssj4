<template>
    <div>
        <h1>Edit Role</h1>
        <form @submit.prevent="submit">
            <div>
                <label for="name">Name</label>
                <input type="text" v-model="form.name" id="name">
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
    role: Object
});

const form = useForm({
    name: role.name
});

function submit() {
    form.put(`/roles/${role.id}`);
}
</script>