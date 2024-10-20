<script setup lang="ts">
import Modal, { type Props, type Emits } from "./Modal.vue";

defineProps<Partial<Props>>();

const emit = defineEmits<Emits>();

const close = () => emit("close");
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="px-6 py-4">
            <div class="text-2023-red text-lg font-medium">
                <slot name="title" />
            </div>

            <div class="text-2023-red-dark mt-4 text-sm">
                <slot name="content" />
            </div>
        </div>

        <div class="bg-2023-bg flex flex-row justify-end px-6 py-4 text-right">
            <slot name="footer" />
        </div>
    </Modal>
</template>
