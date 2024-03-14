<script setup>
import { ref, reactive, onMounted, defineAsyncComponent } from "vue";
import { useRouter } from "vue-router";
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
const forecastStore = useForecastStore();
const { setForecastData } = useForecastStore();
const { singgleForecast, multiForecast } = storeToRefs(forecastStore);
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

const cekAvailableForecast = () => {
    if (singgleForecast.value == null) {
        router.push({ name: "peramalan" });
    } else {
        getChart();
    }
};

const getChart = () => {
    const detailForecast = singgleForecast.value?.hasil.detailForecast;

    console.log(detailForecast);
    if (detailForecast) {
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
    }
};

const savePeramalan = async () => {
    try {
        const payload = {
            idVaksin: singgleForecast.value.idVaksin,
            alpha: singgleForecast.value.alpha,
            periode: singgleForecast.value.periode,
            bulan: singgleForecast.value.bulan,
            tahun: singgleForecast.value.tahun,
        };
        const { data } = await axiosInstance({
            url: "/peramalan",
            method: "POST",
            data: payload,
        });

        notify({
            text: "Berhasil tambah peramalan",
            type: "success",
            duration: 2000,
        });

        router.push({ name: "peramalan" });
    } catch (e) {
        if (e?.response?.status == 401) {
            localStorage.removeItem("TOKEN");
            location.reload();
        } else if (e?.response?.status == 422) {
            const err = e.response.data.errors;
            errors.idVaksin = err.idVaksin ? err.idVaksin[0] : null;
            errors.range = err.range ? err.range[0] : null;
            errors.periode = err.periode ? err.periode[0] : null;
            errors.alpha = err.alpha ? err.alpha[0] : null;
        } else {
            console.log(e);
            notify({
                text: "Faliled to add, Server is Maintenent",
                type: "error",
                duration: 2000,
            });
        }
    }
};

const buangPeramalan = () => {
    setForecastData(null, "singgle");
    router.push({ name: "peramalan" });
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
    // getChart();
    cekAvailableForecast();
});
</script>

<template>
    <div class="w-full box-border">
        <div
            class="w-full relative overflow-x-auto shadow-md sm:rounded-lg border border-border bg-card text-card-foreground shadow"
        >
            <div
                class="box-border px-6 py-3 border-b border-border flex flex-col lg:flex-row lg:justify-between lg:items-center gap-2"
            >
                <div>
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
                <div class="flex items-center justify-center gap-2">
                    <div class="flex items-center">
                        <Button
                            variant="outline"
                            size="sm"
                            @click.prevent="buangPeramalan()"
                        >
                            <Trash2 class="w-4 h-4 mr-3" />

                            <div class="text-xs">Buang</div>
                        </Button>
                    </div>
                    <div class="flex items-center">
                        <Button
                            variant="outline"
                            size="sm"
                            @click.prevent="savePeramalan()"
                        >
                            <Save class="w-4 h-4 mr-3" />

                            <div class="text-xs">Simpan Hasil Peramalan</div>
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
