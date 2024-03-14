<script setup>
import { ref, reactive, onMounted, defineAsyncComponent } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useForecastStore } from "@/stores/forecast";
import { storeToRefs } from "pinia";
import { useNotification } from "@kyvg/vue3-notification";

const AreaChart = defineAsyncComponent(() =>
    import("@/components/chart/AreaChart.vue")
);

import { Button } from "@/components/UI/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/UI/dropdown-menu";

import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/components/UI/select";

import {
    FilePlus,
    X,
    Save,
    SquareGanttChart,
    FilePenLine,
    Trash2,
    ChevronsUpDown,
} from "lucide-vue-next";

import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/UI/table";

const router = useRouter();
const route = useRoute();
const { notify } = useNotification();
const listthead = ref([
    "no.",
    "bulan",
    "tahun",
    "aktual",
    "forecast",
    "MAD",
    "MSE",
    "MAPE",
]);

const tampilan = ref("detail");
const singgleForecast = ref(null);

const dataset = reactive([
    {
        name: "aktual",
        data: [],
    },
    {
        name: "forecast",
        data: [],
    },
]);

const fetchData = async () => {
    try {
        const { data } = await axiosInstance({
            url: `/peramalan/${route.params.id}/show`,
            method: "GET",
        });

        singgleForecast.value = data.data;

        const detailForecast = data.data.hasil.detailForecast;

        detailForecast.forEach((el) => {
            let title = `${limitCharacter(el.namaBulan)} ${el.tahun}`;

            dataset[0].data.push({
                x: title.toUpperCase(),
                y: el.actual,
            });

            dataset[1].data.push({
                x: title.toUpperCase(),
                y: el.forecast,
            });
        });
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
    }
};

const limitCharacter = (doc) => {
    const nama = doc;
    const panjang = doc.length;

    if (panjang < 3) {
        return nama;
    }
    const data = doc.split("");
    let newName = [];
    data.forEach((element, index) => {
        if (index < 3) {
            newName.push(element);
        }
    });

    return newName.join("");
};

onMounted(() => {
    fetchData();
});
</script>

<template>
    <div class="w-full box-border">
        <div
            class="w-full relative overflow-x-auto shadow-md sm:rounded-lg border border-border bg-card text-card-foreground shadow"
        >
            <div
                class="box-border px-6 py-3 border-b border-border flex flex-col lg:flex-row lg:justify-between lg:items-center"
            >
                <div class="mr-10 lg:mr-0 mb-5 lg:mb-0">
                    <Select v-model="tampilan" class="border-border">
                        <SelectTrigger>
                            <div class="flex items-center justify-center gap-2">
                                <div class="text-muted-foreground">
                                    Tampilkan :
                                </div>
                                <SelectValue class="mr-1" />
                            </div>
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem value="detail"> Detail </SelectItem>
                                <SelectItem value="grafik"> Grafik </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
                <div
                    class="rounded border border-border px-1.5 lg:px-3 py-2 text-xs flex flex-col lg:flex-row item-center justify-center gap-2 text-xs"
                >
                    <div class="flex items-center justify-center gap-2">
                        <div class="text-muted-foreground">Vaksin :</div>
                        <div>{{ singgleForecast?.vaksin }}</div>
                    </div>

                    <div class="w-full lg:w-px h-px lg:h-4 bg-border"></div>
                    <div class="flex items-center justify-center gap-2">
                        <div class="text-muted-foreground">Alpha :</div>
                        <div>{{ singgleForecast?.alpha }}</div>
                    </div>

                    <div class="w-full lg:w-px h-px lg:h-4 bg-border"></div>
                    <div class="flex items-center justify-center gap-2">
                        <div class="text-muted-foreground">Bulan :</div>
                        <div>
                            {{ singgleForecast?.bulan }}/{{
                                singgleForecast?.tahun
                            }}
                        </div>
                    </div>
                </div>
                <div
                    class="absolute lg:relative right-2 top-2 lg:right-0 lg:top-0 flex items-center justify-center"
                >
                    <div class="flex items-center">
                        <Button
                            variant="outline"
                            size="icon"
                            @click.prevent="router.push({ name: 'peramalan' })"
                        >
                            <X class="w-4 h-4" />
                        </Button>
                    </div>
                </div>
            </div>

            <div
                v-if="tampilan == 'detail'"
                class="relative overflow-x-auto w-full text-xs"
            >
                <Table v-if="singgleForecast?.hasil.detailForecast.length > 0">
                    <TableHeader class="bg-muted/50">
                        <TableRow>
                            <TableHead
                                v-for="item in listthead"
                                class="capitalize border-r border-border"
                                :class="
                                    item == 'MAD' ||
                                    item == 'MSE' ||
                                    item == 'MAPE'
                                        ? 'text-end'
                                        : 'text-center'
                                "
                            >
                                {{ item }}
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="(item, i) in singgleForecast.hasil
                                .detailForecast"
                        >
                            <TableCell class="border-r border-border"
                                >{{ i + 1 }}.</TableCell
                            >
                            <TableCell
                                class="border-r border-border capitalize"
                            >
                                {{ item.namaBulan }}
                            </TableCell>
                            <TableCell class="border-r border-border">{{
                                item.tahun
                            }}</TableCell>
                            <TableCell
                                class="text-end border-r border-border"
                                >{{ item.actual }}</TableCell
                            >
                            <TableCell class="text-end border-r border-border">
                                {{ item.forecast }}
                            </TableCell>
                            <TableCell class="text-end border-r border-border">
                                {{ item.mad }}
                            </TableCell>
                            <TableCell class="text-end border-r border-border">
                                {{ item.mse }}
                            </TableCell>
                            <TableCell class="text-end">
                                {{ item.mape }}
                            </TableCell>
                        </TableRow>
                        <TableRow>
                            <TableCell
                                colspan="4"
                                class="border-r border-border"
                                >Rata - rata</TableCell
                            >
                            <TableCell class="border-r border-border">
                                <div class="sr-only">Rata - rata</div>
                            </TableCell>
                            <TableCell class="text-end border-r border-border">
                                {{ singgleForecast?.hasil?.average?.mad }}
                            </TableCell>
                            <TableCell class="text-end border-r border-border">
                                {{ singgleForecast?.hasil?.average?.mse }}
                            </TableCell>
                            <TableCell class="text-end">
                                {{ singgleForecast?.hasil?.average?.mape }}
                            </TableCell>
                        </TableRow>
                        <TableRow>
                            <TableCell
                                colspan="4"
                                class="border-r border-border"
                            >
                                Prediksi
                                {{
                                    singgleForecast?.hasil?.nextForecast
                                        ?.namaBulan
                                }}
                                {{
                                    singgleForecast?.hasil?.nextForecast?.tahun
                                }}
                                ?
                            </TableCell>
                            <TableCell class="border-r border-border">
                                {{
                                    singgleForecast?.hasil?.nextForecast
                                        ?.forecast
                                }}
                            </TableCell>
                            <TableCell colspan="3" class="text-end">
                                {{
                                    singgleForecast?.hasil?.average?.mape * 100
                                }}
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div v-if="tampilan == 'grafik'" class="">
                <h1 class="mb-2 box-border p-3">
                    Grafik Perbandingan Peramalan
                </h1>
                <AreaChart class="lg:col-span-4" :data="dataset" />

                <div class="mb-2 box-border p-3 flex gap-3 capitalize">
                    <span
                        >Prediksi
                        {{ singgleForecast?.hasil?.nextForecast?.namaBulan }}
                        {{ singgleForecast?.hasil?.nextForecast?.tahun }}
                        Adalah</span
                    >

                    <span class="font-semibold">{{
                        singgleForecast?.hasil?.nextForecast?.forecast
                    }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
