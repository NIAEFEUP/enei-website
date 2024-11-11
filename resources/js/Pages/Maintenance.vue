<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted } from 'vue';
const targetDateEnv = import.meta.env.VITE_TARGET_DATE;

const targetDate = new Date(targetDateEnv).getTime();
const days = ref(0);
const hours = ref(0);
const minutes = ref(0);
const seconds = ref(0);

const updateCountdown = () => {
  const now = new Date().getTime();
  const distance = targetDate - now;

  if (distance > 0) {
    days.value = Math.floor(distance / (1000 * 60 * 60 * 24));
    hours.value = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    minutes.value = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    seconds.value = Math.floor((distance % (1000 * 60)) / 1000);
  } else {
    days.value = 0;
    hours.value = 0;
    minutes.value = 0;
    seconds.value = 0;
  }
};

onMounted(() => {
  updateCountdown();
  setInterval(updateCountdown, 1000);
});
</script>

<template>

    <AppLayout title="Home" class="flex">

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
                <div class=" flex space-x-4 mt-10 ">
                <div class="flex flex-col items-center bg-enei-beige opacity-75 p-8 w-32">
                    <p class="text-enei-blue text-5xl font-bold ">{{ days }}</p>
                    <p class="text-enei-blue text-xl font-bold mt-3">DIAS</p>
                </div>
                <div class="flex flex-col items-center bg-enei-beige opacity-75 p-8 w-32">
                    <p class="text-enei-blue text-5xl font-bold">{{ hours }}</p>
                    <p class="text-enei-blue text-xl font-bold mt-3">HORAS</p>
                </div>
                <div class="flex flex-col items-center bg-enei-beige opacity-75 p-8 w-32">
                    <p class="text-enei-blue text-5xl font-bold">{{ minutes }}</p>
                    <p class="text-enei-blue text-xl font-bold mt-3">MINUTOS</p>
                </div>
                <div class="flex flex-col items-center bg-enei-beige opacity-75 p-8 w-32">
                    <p class="text-enei-blue text-5xl font-bold">{{ seconds }}</p>
                    <p class="text-enei-blue text-xl font-bold mt-3">SEGUNDOS</p>
                </div>
                </div>
            </div>
            <div class="mx-auto">
                <p
                    class="mr-2 w-48 bg-enei-beige p-2.5 px-8 text-center font-space-grotesk text-lg font-bold text-enei-blue uppercase"
                >
                    {{ $t("pages.maintenance.soon") }}
                </p>
            </div>
        </section>
    </AppLayout>
</template>

<style>
.background {
    background-image: url("/images/landing_page.jpg");
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
