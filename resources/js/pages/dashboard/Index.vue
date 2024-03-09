<script setup>
import { defineAsyncComponent, onMounted, ref } from "vue";
const AreaChart = defineAsyncComponent(() =>
    import("@/components/chart/AreaChart.vue")
);

const dataset = ref([
    {
        name: "sprkb",
        data: [
            {
                x: "01/10/2023",
                y: 20,
            },
            {
                x: "01/11/2023",
                y: 10,
            },
            {
                x: "01/12/2023",
                y: 10,
            },
        ],
    },
    {
        name: "formulir",
        data: [
            {
                x: "01/10/2023",
                y: 5,
            },
            {
                x: "01/11/2023",
                y: 6,
            },
            {
                x: "01/12/2023",
                y: 6,
            },
        ],
    },
]);
</script>

<template>
    <div>
        <AreaChart class="lg:col-span-4" :data="dataset" />
    </div>
</template>
