<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar.vue";
//import Footer from "@/Components/Footer.vue";
//import Banner from "@/Components/Banner.vue";

interface Props {
    title: string;
}

defineProps<Props>();

</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Head :title="title" />
        <div class="sticky left-0 right-0 top-0 z-30">
            <Navbar />
        </div>
        <main :class="$attrs.class" class="flex-1">
            <slot />
        </main>
    </div>
</template>

<style>
html {
    scroll-behavior: smooth;
}
</style>
