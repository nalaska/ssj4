<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Feuille de Présence</h1>
        <AttendanceCard 
            :userName="userName" 
            :initialAttendance="attendance" 
            :userId="userId" 
            :userPicture="userPicture" 
            :userBelt="userBelt" 
        />
    </div>
    </AuthenticatedLayout>
    
</template>

<script setup>
    import { ref } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import AttendanceCard from '@/Components/AttendanceCard.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

    const { props } = usePage();
    const userName = ref(props.user.name || '');
    const attendance = ref(JSON.parse(props.user.attendance || '{}'));
    const userId = ref(props.user.id || null);
    const userPicture = ref(props.user.picture || 'null');
    const userBelt = ref(props.user.belt || 'null');
</script>
