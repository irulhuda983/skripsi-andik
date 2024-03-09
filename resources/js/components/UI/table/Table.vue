<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
    class: { type: null, required: false },
});
</script>

<template>
    <div class="relative w-full">
        <table
            :class="cn('w-full text-left caption-bottom text-sm', props.class)"
        >
            <slot />
        </table>
    </div>
</template>
