<script setup>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useForecastStore } from "@/stores/forecast";
import { storeToRefs } from "pinia";
import { useNotification } from "@kyvg/vue3-notification";

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

const fetchData = async () => {
    try {
        const { data } = await axiosInstance({
            url: `/peramalan/${route.params.id}/show`,
            method: "GET",
        });

        singgleForecast.value = data.data;
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
                class="box-border px-6 py-3 border-b border-border flex justify-between items-center"
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
                                <SelectItem value="grafik"> Grafik </SelectItem>
                                <SelectItem value="detail"> Detail </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
                <div
                    class="rounded border border-border px-3 py-2 text-xs flex item-center justify-center gap-2"
                >
                    <div class="flex items-center justify-center gap-2">
                        <div class="text-muted-foreground">Vaksin :</div>
                        <div>{{ singgleForecast?.vaksin }}</div>
                    </div>

                    <div class="w-px h-4 bg-border"></div>
                    <div class="flex items-center justify-center gap-2">
                        <div class="text-muted-foreground">Alpha :</div>
                        <div>{{ singgleForecast?.alpha }}</div>
                    </div>

                    <div class="w-px h-4 bg-border"></div>
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
                            size="icon"
                            @click.prevent="router.push({ name: 'peramalan' })"
                        >
                            <X class="w-4 h-4" />
                        </Button>
                    </div>
                </div>
            </div>

            <div class="relative overflow-x-auto w-full text-xs">
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
        </div>
    </div>
</template>