<script setup lang="ts">
import type Competition from "@/Types/Competition";
import route from "ziggy-js";
import { Link } from "@inertiajs/vue3";

interface Props {
    competition: Competition;
}

defineProps<Props>();

const formattedDate = (
    startDate: string,
    separator: string,
    endDate: string,
) => {
    const startDateArray = startDate.split(" ");
    const endDateArray = endDate.split(" ");

    let pointer = 0;

    while (startDateArray[pointer] === endDateArray[pointer]) pointer++;

    endDate = endDateArray.slice(pointer).join(" ");

    return `${startDate} ${separator} ${endDate}`;
};
</script>

<template>
    <article
        class="border-2023-teal-dark relative flex flex-col border-b-2 pb-4"
    >
        <h2 class="text-2023-orange text-2xl font-bold">
            <em
                ><Link
                    :href="route('competition.show', competition)"
                    preserve-state
                    preserve-scroll
                    >{{ competition.name }}</Link
                ></em
            >
        </h2>
        <p class="text-2023-teal-dark text-lg">{{ competition.theme }}</p>
        <span class="text-2023-teal">
            {{
                formattedDate(
                    $d(new Date(competition.date_start), "fullTime"),
                    "-",
                    $d(new Date(competition.date_end), "fullTime"),
                )
            }}
        </span>
        <span
            class="bg-2023-orange absolute -left-[calc(2rem+17.75px)] top-0 inline-flex h-8 w-8 items-center justify-center rounded-sm text-xl font-semibold text-white"
            >i</span
        >
    </article>
</template>
