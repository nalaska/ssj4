<template>
    <div>
        <h1>Roles</h1>
        <Link href="/roles/create" class="btn btn-primary">Add Role</Link>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="role in roles" :key="role.id">
                    <td>{{ role.name }}</td>
                    <td>
                        <Link :href="`/roles/${role.id}/edit`" class="btn btn-warning">Edit</Link>
                        <form :action="`/roles/${role.id}`" method="POST" style="display:inline;">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
    roles: Array
});
</script>