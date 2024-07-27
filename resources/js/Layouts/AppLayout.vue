<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar.vue";
//import Footer from "@/Components/Footer.vue";
//import Banner from "@/Components/Banner.vue";

interface Props {
    title: string;
    showNavbar?: boolean;
}

withDefaults(defineProps<Props>(), {
    showNavbar: true,
});
</script>

<template>
    <div>
        <Head :title="title" />
        <div v-show="showNavbar" class="sticky left-0 right-0 top-0 z-30">
            <Navbar />
        </div>
        <main class="min-h-screen">
            <slot />
        </main>
    </div>
</template>

<style>
html {
    scroll-behavior: smooth;
}
</style>
