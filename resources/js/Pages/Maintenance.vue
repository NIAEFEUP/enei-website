<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Navbar from "@/Components/Navbar.vue";
</script>

<template>
    <AppLayout title="Home" :show-navbar="false">
        <div class="background min-h-screen flex flex-col">
            <div class="sticky left-0 right-0 top-0 z-30">
                <Navbar />
            </div>
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
