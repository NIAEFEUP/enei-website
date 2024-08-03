<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
/*import SpeakersCarousel from "@/Components/Home/SpeakersCarousel.vue";
import SponsorBanner from "@/Components/Home/SponsorBanner.vue";
import EnrollSection from "@/Components/Home/EnrollSection.vue";*/
import type Edition from "@/Types/Edition";
import type EventDay from "@/Types/EventDay";
import type { User } from "@/Types/User";
import type SponsorTier from "@/Types/SponsorTier";
import Navbar from "@/Components/Navbar.vue";
//import { default as MapComponent } from "@/Components/Home/Map.vue";
//import InfoPopup from "@/Components/Home/InfoPopup.vue";
//import { OhVueIcon } from "oh-vue-icons";

interface Props {
    edition: Edition;
    sponsorTiers: SponsorTier[];
    speakers: User[];
    days: EventDay[];
    activityCount: number;
    talkCount: number;
    standCount: number;
    canEnroll: boolean;
}

defineProps<Props>();

/*const formattedDate = (
    startDate: string,
    separator: string,
    endDate: string,
) => {
    const startDateArray = startDate.split(" ");
    const endDateArray = endDate.split(" ");

    let pointer = 0;

    while (
        startDateArray[startDateArray.length - pointer - 1] ===
        endDateArray[endDateArray.length - pointer - 1]
    )
        pointer++;

    startDate = startDateArray
        .slice(0, startDateArray.length - pointer)
        .join(" ");

    return `${startDate} ${separator} ${endDate}`;
};*/
</script>

<template>
    <AppLayout title="Home" :show-navbar="false">
        <div class="background min-h-screen flex flex-col">
            <div class="sticky left-0 right-0 top-0 z-30">
                <Navbar />
            </div>
            <!--<InfoPopup v-if="$page.props.auth.user && canEnroll" />
            <a
                v-if="canEnroll"
                href="#enroll-wrapper"
                class="group fixed bottom-10 right-12 z-50 cursor-pointer border border-black bg-2023-red-dark p-2 px-3 font-bold text-white shadow shadow-2023-teal"
            >
            <OhVueIcon name="io-arrow-down" fill="white" />
            </a>-->
            <!-- LOGO & DATE -->
            <section
                class=" grow relative flex flex-col gap-8 py-36 md:justify-evenly md:py-0"
            >
                <div class="mx-auto md:ml-36">
                    <h1
                        class="w-3/12 text-justify font-space-grotesk text-5xl font-bold tracking-tight text-white md:text-7xl"
                    >
                        <p class="whitespace-nowrap">ENCONTRO</p>
                        <p class="whitespace-nowrap">NACIONAL DE</p>
                        <p class="whitespace-nowrap">ESTUDANTES DE</p>
                        <p class="whitespace-nowrap">INFORMÁTICA</p>
                    </h1>
                    <p
                        class="font-space-grotesk text-2xl text-white md:text-4xl"
                    >
                        PORTO 2025
                    </p>
                </div>
                <div class="mx-auto">
                    <p
                        class="mr-2 w-48 bg-2023-bg p-2.5 px-8 text-center font-space-grotesk text-lg text-lg font-bold font-bold text-enei-blue"
                    >
                        BREVEMENTE
                    </p>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<style>
.background {
    background-image: url("/images/landing-page-background.png");
    background-size: cover;
    background-repeat: no-repeat;
}

@media (max-width: 768px) {
    .background {
        background-size: auto 100%; /* Ensures the background zooms in horizontally */
        background-position: bottom;
    }
}
</style>
