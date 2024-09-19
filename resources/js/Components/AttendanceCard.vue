<template>
    <form 
        @submit.prevent="submitAttendance"
    >
        <div class="attendance-card">
            <div class="header">
                <h2>CARTON DE PRESENCE</h2>
                <div class="name-field">
                    <label for="name">NOM / PRENOM :</label>
                    <input type="text" id="name" v-model="props.userName" readonly />
                </div>
                <div class="user-picture" v-if="props.userPicture">
                    <img :src="`/storage/${props.userPicture}`" alt="User Picture" />
                </div>
            </div>
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>MOIS / DATES</th>
                        <th 
                            v-for="day in 31" 
                            :key="day"
                        >{{ day }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="month in months" 
                        :key="month"
                    >
                        <td>{{ month }}</td>
                        <td 
                            v-for="day in 31" 
                            :key="day"
                        >
                            <input type="checkbox" v-if="attendance[month]" v-model="attendance[month][day]" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="actions">
                <button type="submit" class="btn-submit">Valider</button>
            </div>
        </div>
    </form>
</template>

<script setup>
    import { ref, watch } from 'vue';
    import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        userName: String,
        initialAttendance: {
            type: Object,
            required: true
        },
        userId: Number,
        userPicture: String
    });

    const months = [
        'SEPTEMBRE', 'OCTOBRE', 'NOVEMBRE', 'DECEMBRE', 'JANVIER', 
        'FEVRIER', 'MARS', 'AVRIL', 'MAI', 'JUIN', 'JUILLET', 'AOUT'
    ];

    function initializeAttendance(initialAttendance) {
        const attendanceObj = {};
        months.forEach(month => {
            attendanceObj[month] = {};
            for (let day = 1; day <= 31; day++) {
                attendanceObj[month][day] = initialAttendance[month]?.[day] || false;
            }
        });
        return attendanceObj;
    }

    const attendance = ref(initializeAttendance(props.initialAttendance));

    const form = useForm({
        attendance: attendance.value
    });

    function submitAttendance() {
        form.attendance = JSON.stringify(attendance.value);
        form.post(`/users/${props.userId}/attendance`);
    }

    watch(attendance, (newVal) => {
        form.attendance = JSON.stringify(newVal);
    }, { deep: true });
</script>

<style scoped>
.attendance-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 16px;
    margin: 16px;
    max-width: 100%;
    overflow-x: auto;
}

.header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 16px;
    position: relative;
}

.name-field {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 8px;
}

.name-field label {
    margin-bottom: 4px;
}

.name-field input {
    width: 100%;
    max-width: 300px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.user-picture {
    position: absolute;
    top: 0;
    right: 0;
    margin: 16px;
}

.user-picture img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.attendance-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
}

.attendance-table th, .attendance-table td {
    border: 1px solid #ddd;
    text-align: center;
    padding: 8px;
}

.attendance-table th {
    background-color: #f4f4f4;
}

.actions {
    display: flex;
    justify-content: center;
    margin-top: 16px;
}

.btn-submit {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-submit:hover {
    background-color: #45a049;
}

@media (max-width: 768px) {
    .attendance-table th, .attendance-table td {
        padding: 4px;
    }

    .attendance-table th {
        font-size: 12px;
    }

    .attendance-table td {
        font-size: 10px;
    }
}

@media (max-width: 480px) {
    .header h2 {
        font-size: 18px;
    }

    .name-field input {
        max-width: 100%;
    }

    .attendance-table th, .attendance-table td {
        padding: 2px;
    }

    .attendance-table th {
        font-size: 10px;
    }

    .attendance-table td {
        font-size: 8px;
    }
}
</style>