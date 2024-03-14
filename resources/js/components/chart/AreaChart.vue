<script setup>
import { ref, watch } from "vue";
import apexchart from "vue3-apexcharts";

const props = defineProps(["data", "type"]);

const colorLine = ref("#3780f6");
const series = ref(getSeries());
const options = ref(getOption());

watch(
    () => props.data,
    (e) => {
        series.value = getSeries();
        options.value = getOption();
    },
    { deep: true }
);

function getSeries() {
    return [
        {
            name: "Value",
            data: props.data,
            color: colorLine.value,
        },
    ];
}

function getOption() {
    return {
        chart: {
            toolbar: {
                show: false,
            },
            foreColor: "#f2f2f2",
            type: "line",
        },
        tooltip: {
            theme: "dark",
        },
        markers: {
            size: 4,
        },
        dataLabels: {
            enabled: false,
        },
        grid: {
            show: false,
        },
        colors: ["#3780f6", "#22c35d"],
        stroke: {
            show: true,
            curve: "smooth",
            width: 2,
        },
        legend: {
            show: false,
        },
        // xaxis: {
        //     type: "datetime",
        //     labels: {
        //         format: "dd MMM",
        //     },
        // },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return val.toFixed(0);
                },
            },
        },
    };
}
</script>
<template>
    <div class="w-full h-[350px] pr-3 pl-1 py-3 rounded-xl shadow-md">
        <apexchart
            width="100%"
            height="100%"
            :options="options"
            :series="props.data"
        ></apexchart>
    </div>
</template>
