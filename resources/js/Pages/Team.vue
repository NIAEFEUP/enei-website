<script setup lang="ts">
import type Department from "@/Types/Department";
import AppLayout from "@/Layouts/AppLayout.vue";
import SINFTeam from "@/Components/Team/SINFTeam.vue";

interface Props {
    departments: Department[];
}

defineProps<Props>();

const colors: Array<string> = [
    "orange",
    "teal-dark",
    "red-dark",
    "red",
    "teal",
];
</script>

<template>
    <AppLayout title="Equipa">
        <div
            v-if="departments.length > 0"
            class="flex flex-col items-center gap-7 pt-10"
        >
            <h1
                class="bg-2023-red shadow-2023-bg w-fit border border-black p-2 px-5 text-2xl font-bold text-white shadow-md"
            >
                Equipa
            </h1>
            <SINFTeam
                v-for="department in departments"
                :key="department.id"
                :color="colors[department.id % colors.length]"
                :department="department"
            ></SINFTeam>
        </div>
        <div v-else>
            <p class="text-2023-teal-dark pt-80 text-center text-5xl font-bold">
                Em breve...
            </p>
        </div>
    </AppLayout>
</template>

<style scoped>
main {
    padding: 2rem;
    padding-inline: 4rem;
}
</style>
