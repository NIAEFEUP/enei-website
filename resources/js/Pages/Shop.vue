<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import ShopItem from "@/Components/Shop/ShopItem.vue";
import { type BuyableProduct } from "@/Types/ShopPage";

interface Props {
    products: BuyableProduct[];
    points?: number;
    isEnrolled: boolean;
    isParticipant: boolean;
}

defineProps<Props>();
</script>

<template>
    <AppLayout title="Loja">
        <template v-if="products.length > 0">
            <div
                class="flex content-center items-center justify-center self-center py-10"
            >
                <h1
                    class="bg-2023-red shadow-2023-bg w-fit border border-black p-2 px-5 text-2xl font-bold text-white shadow-md"
                >
                    Loja
                </h1>
            </div>
            <p
                v-if="points !== null"
                class="text-2023-teal-dark py-5 text-center text-xl font-bold"
            >
                Tens {{ points }}
                <img
                    class="inline w-5 align-text-top"
                    alt="SINFrão"
                    title="SINFrão"
                    src="/images/cy-sinf-small.svg"
                />
            </p>
            <section
                class="align-center grid justify-center gap-10 px-32"
                style="
                    grid-template-columns: repeat(
                        auto-fill,
                        minmax(18em, 20em)
                    );
                "
            >
                <ShopItem
                    v-for="product in products"
                    :key="product.id"
                    :product="product"
                    :is-participant="isParticipant"
                    :is-enrolled="isEnrolled"
                />
            </section>
        </template>
        <template v-else>
            <div class="flex items-center justify-center">
                <p
                    class="text-2023-teal-dark pt-80 text-center text-5xl font-bold"
                >
                    Em breve...
                </p>
            </div>
        </template>
    </AppLayout>
</template>
