<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import ProgramDayPanel from "@/Components/Program/ProgramDayPanel.vue";
import type EventDay from "@/Types/EventDay";
import route from "ziggy-js";
import { Link } from "@inertiajs/vue3";

interface Props {
    eventDay?: EventDay;
    queryDay: number;
    totalDays: number;
}

defineProps<Props>();
</script>

<template>
    <AppLayout title="Programa">
        <div
            v-if="totalDays !== 0 && eventDay !== undefined"
            class="flex flex-col items-center px-10 py-10 md:px-40"
        >
            <h1
                class="bg-2023-red shadow-2023-bg mb-10 w-fit border border-black p-2 px-5 text-2xl font-bold text-white shadow-md"
            >
                Programa
            </h1>
            <section class="mb-5 flex flex-col items-center gap-5">
                <div
                    id="daySelection"
                    class="flex w-fit flex-row flex-wrap justify-center gap-4"
                >
                    <template v-for="(day, idx) in totalDays" :key="idx">
                        <Link
                            :href="
                                route(route().current() ?? 'program', {
                                    day,
                                })
                            "
                            as="span"
                            :only="['eventDay', 'queryDay']"
                            class="bg-2023-teal inline-flex h-16 w-16 cursor-pointer items-center justify-center rounded-sm text-xl font-bold text-white transition"
                            :class="{
                                selected:
                                    day ==
                                    queryDay /* cringe Inertia gives us a string, no strict equality boo-hoo */,
                            }"
                            preserve-state
                            preserve-scroll
                        >
                            {{ day }}
                        </Link>
                    </template>
                </div>
                <span class="text-2023-orange font-bold">{{
                    $d(new Date(eventDay.date), "long")
                }}</span>
            </section>
            <ProgramDayPanel :key="eventDay.id" :day="eventDay" />
        </div>
        <div v-else class="flex items-center justify-center">
            <p class="text-2023-teal-dark pt-80 text-center text-5xl font-bold">
                Em breve...
            </p>
        </div>
    </AppLayout>
</template>

<style scoped>
.selected {
    background-color: rgb(242, 147, 37);
}
</style>
