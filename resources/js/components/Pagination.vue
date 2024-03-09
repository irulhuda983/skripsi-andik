<script setup>
import { reactive } from "vue";
import { VuePagination } from "vue3-tailwind-pagination";

const props = defineProps(["total", "currentPage", "perPage"]);
const emit = defineEmits(["changePage"]);

const classes = reactive({
    wrapper: "flex text-center mx-auto shadow-sm text-sm",
    element: "w-6 h-6 border border-border table-cell hover:border-border",
    activeElement:
        "w-6 h-6 border border-border table-cell hover:border-border",
    disabledElement:
        "w-6 h-6 border border-border table-cell cursor-not-allowed",
    activeButton: "bg-accent w-full h-full text-white",
    disabledButton:
        "opacity-70 w-full h-full pointer-events-none transition duration-100 ease-in-out",
    button: "w-full h-full transition duration-100 ease-in-out focus:ring-2 focus:ring-border focus:outline-none",
});

const changePage = (page) => {
    emit("changePage", page);
};
</script>

<template>
    <VuePagination
        :total="props.total"
        v-model:value="props.currentPage"
        :perPage="parseInt(props.perPage)"
        @set="changePage"
        :classes="classes"
    />
</template>
