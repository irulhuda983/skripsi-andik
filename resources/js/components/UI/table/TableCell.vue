<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
    class: { type: null, required: false },
});
</script>

<template>
    <td
        :class="
            cn(
                'p-3 align-middle [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-0.5 [&:has([role=checkbox])]:pl-3',
                props.class
            )
        "
    >
        <slot />
    </td>
</template>
