<template>
    <div>
        <h1>Create Role</h1>
        <form @submit.prevent="submit">
            <div>
                <label for="name">Name</label>
                <input type="text" v-model="form.name" id="name">
            </div>
            <button type="submit">Create</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: ''
});

function submit() {
    form.post('/roles');
}
</script>