<script setup>
import { defineAsyncComponent, onMounted, ref } from "vue";
import { useNotification } from "@kyvg/vue3-notification";
const AreaChart = defineAsyncComponent(() =>
    import("@/components/chart/AreaChart.vue")
);

import { Syringe, ListTodo, Activity, History } from "lucide-vue-next";

const { notify } = useNotification();
const isLoading = ref(false);
const jumlahData = ref({
    history: 0,
    transaksi: 0,
    vaksin: 0,
});
const dataset = ref([]);

const fetchData = async () => {
    isLoading.value = true;
    try {
        const { data } = await axiosInstance({
            url: `/dashboard/chart`,
            method: "GET",
        });

        jumlahData.value = data.jumlah;
        dataset.value = data.chart;
        console.log(data);
    } catch (e) {
        if (e.response.status == 401) {
            localStorage.removeItem("TOKEN");
            location.reload();
        } else {
            notify({
                text: "Faliled to add, Server is Maintenent",
                type: "error",
                duration: 2000,
            });
        }
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchData();
});
</script>

<template>
    <div class="w-full box-border">
        <div
            class="w-full box-border flex items-center flex-col lg:flex-row justify-between gap-3 mb-5"
        >
            <div
                class="w-full lg:w-80 h-24 rounded border border-border box-border p-4 flex items-center"
            >
                <div class="w-full">
                    <h5 class="text-[10px] text-primary/40">Jenis Vaksin</h5>
                    <div class="text-[40px] font-semibold">
                        {{ jumlahData.vaksin }}
                    </div>
                </div>
                <div class="w-20 flex-none flex items-center justify-center">
                    <ListTodo class="w-12 h-12 text-blue-500" />
                </div>
            </div>
            <div
                class="w-full lg:w-80 h-24 rounded border border-border box-border p-4 flex items-center"
            >
                <div class="w-full">
                    <h5 class="text-[10px] text-primary/40">
                        Penggunaan Vaksin
                    </h5>
                    <div class="text-[40px] font-semibold">
                        {{ jumlahData.transaksi }}
                    </div>
                </div>
                <div class="w-20 flex-none flex items-center justify-center">
                    <Activity class="w-12 h-12 text-teal-500" />
                </div>
            </div>
            <div
                class="w-full lg:w-80 h-24 rounded border border-border box-border p-4 flex items-center"
            >
                <div class="w-full">
                    <h5 class="text-[10px] text-primary/40">
                        Histori Peramalan
                    </h5>
                    <div class="text-[40px] font-semibold">
                        {{ jumlahData.history }}
                    </div>
                </div>
                <div class="w-20 flex-none flex items-center justify-center">
                    <History class="w-12 h-12 text-violet-500" />
                </div>
            </div>
        </div>
        <div class="w-full box-border border border-border rounded">
            <h1 class="mb-2 box-border p-3 text-primary/60">
                Grafik Penggunaan Vaksin
            </h1>
            <AreaChart class="lg:col-span-4" :data="dataset" />
        </div>
    </div>
</template>
