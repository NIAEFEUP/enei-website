<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import Banner from "@/Components/Banner.vue";

interface Props {
    title: string;
}

defineProps<Props>();
</script>

<template>
    <div>
        <Head :title="title" />
        <div class="test">
            <div class="sticky top-0 z-30">
                <Navbar />
                
            </div>
            <main class="min-h-screen ">
                <slot />
            </main>
        </div>
        <Footer />
    </div>
</template>

<style>
.test{
    background-image: url('/images/landing-page-background.png');
    background-size: cover;
    background-repeat: no-repeat;
}
html {
    scroll-behavior: smooth;
}
</style>
