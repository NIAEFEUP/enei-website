<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";

</script>

<template>

    <AppLayout title="Home" class="flex flex-col">

        <div class="absolute inset-0 background z-2"></div>
        <div class="absolute inset-0 gradient-overlay z-0"></div>

        <section
            class="grow relative flex flex-col gap-8 px-5 py-36 md:justify-evenly md:py-0 md:px-0 z-10"
        >
            <div class="mx-auto md:ml-36 flex-grow md:flex-grow-0">
                <h1
                    class="w-3/12 text-justify font-space-grotesk text-5xl font-bold tracking-tight text-white md:text-7xl"
                >
                    <p class="whitespace-nowrap lowercase">Encontro</p>
                    <p class="whitespace-nowrap lowercase">Nacional de</p>
                    <p class="whitespace-nowrap lowercase">Estudantes de</p>
                    <p class="whitespace-nowrap lowercase">Informática</p>
                </h1>
                <p
                    class="font-space-grotesk text-2xl text-white md:text-4xl uppercase"
                >
                    Porto 2025
                </p>
            </div>
            <div class="mx-auto">
                <p
                    class="mr-2 w-48 bg-enei-beige p-2.5 px-8 text-center font-space-grotesk text-lg font-bold text-enei-blue uppercase"
                >
                    Brevemente
                </p>
            </div>
        </section>
    </AppLayout>
</template>

<style>
.background {
    background-image: url("/images/background.jpeg");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}

.gradient-overlay {
    mix-blend-mode: plus-darker;
    background: linear-gradient(180deg, #0B4F6C 8%, #EFE3CA 100%);
    opacity: 92%;
}


@media (max-width: 768px) {
    .background {
        /*background-size: auto 100%;  Ensures the background zooms in horizontally */
        background-position: bottom;
    }
}
</style>
