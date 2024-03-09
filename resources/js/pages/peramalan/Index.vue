<script setup>
import { ref, reactive, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import { useFormatJsonToFormData } from "@/composables/jsonformater";
import { useNotification } from "@kyvg/vue3-notification";
import { useForecastStore } from "@/stores/forecast";
import { storeToRefs } from "pinia";
import Pagination from "@/components/Pagination.vue";
import Search from "@/components/UI/Search.vue";
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
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/UI/table";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/UI/dropdown-menu";
import { Input } from "@/components/UI/input";
import { Label } from "@/components/UI/label";
import { Button } from "@/components/UI/button";
import {
    FilePlus,
    X,
    Save,
    SquareGanttChart,
    FilePenLine,
    Trash2,
    ChevronsUpDown,
} from "lucide-vue-next";
import Modal from "@/components/UI/Modal.vue";
import LoadingTable from "@/components/LoadingTable.vue";
import EmptyTable from "@/components/EmptyTable.vue";
import DropdownSearch from "@/components/UI/DropdownSearch.vue";

const forecastStore = useForecastStore();
const { setForecastData } = useForecastStore();
const { singgleForecast, multiForecast } = storeToRefs(forecastStore);
const { notify } = useNotification();
const router = useRouter();
const modalTambah = ref();
const modalDelete = ref();
const isLoading = ref(false);
const datalist = ref([]);
const listtahun = ref([]);
const listbulan = ref([]);
const listvaksin = ref([]);
const listthead = ref([
    "no.",
    "vaksin",
    "bulan",
    "tahun",
    "alpha",
    "hasil",
    "MAD",
    "MSE",
    "MAPE",
]);

const pageInfo = ref({
    current_page: 1,
    from: null,
    last_page: 1,
    per_page: 10,
    to: null,
    total: 0,
});

const params = reactive({
    search: "",
    tahun: new Date().getFullYear(),
    page: 1,
    limit: 10,
    order: "nama:asc",
});

const payload = reactive({
    id: null,
    range: null,
    idVaksin: null,
    periode: null,
    alpha: null,
    bulan: null,
    tahun: null,
});

const errors = reactive({
    id: null,
    range: null,
    idVaksin: null,
    periode: null,
    alpha: null,
    bulan: null,
    tahun: null,
});

const fetchVaksin = async () => {
    try {
        const { data } = await axiosInstance({
            url: `/opt/vaksin`,
            method: "GET",
        });

        listvaksin.value = data.data;
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

const fetchTahun = async () => {
    try {
        const { data } = await axiosInstance({
            url: `/opt/tahun`,
            method: "GET",
        });

        listtahun.value = data.data;
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

const fetchBulan = async () => {
    try {
        const { data } = await axiosInstance({
            url: `/opt/bulan`,
            method: "GET",
        });

        listbulan.value = data.data;
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

const fetchPeramalan = async () => {
    isLoading.value = true;
    try {
        const { data } = await axiosInstance({
            url: `/peramalan`,
            method: "GET",
        });

        datalist.value = data.data;
        pageInfo.value = data.meta;
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

const savePeramalan = async () => {
    try {
        const { data } = await axiosInstance({
            url: "/peramalan/get-forecast",
            method: "POST",
            data: payload,
        });
        setForecastData(data.data, "singgle");
        router.push({ name: "previewPeramalan" });
        // closeModalTambah();
        // notify({
        //     text: "Berhasil tambah peramalan",
        //     type: "success",
        //     duration: 2000,
        // });
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
            closeModalTambah();
            notify({
                text: "Faliled to add, Server is Maintenent",
                type: "error",
                duration: 2000,
            });
        }
    }
};

const deletePeramalan = async () => {
    try {
        const { data } = await axiosInstance({
            url: `/peramalan/${payload.id}/delete`,
            method: "DELETE",
        });
        fetchPeramalan();
        closeModalDelete();
        notify({
            text: "Berhasil hapus data",
            type: "success",
            duration: 2000,
        });
    } catch (e) {
        if (e.response.status == 401) {
            localStorage.removeItem("TOKEN");
            location.reload();
        } else {
            closeModalDelete();
            notify({
                text: "Faliled to add, Server is Maintenent",
                type: "error",
                duration: 2000,
            });
        }
    }
};

const openModalTambah = () => {
    modalTambah.value.open = true;
};

const closeModalTambah = () => {
    modalTambah.value.open = false;
};

const openModalDelete = (id) => {
    payload.id = id;
    modalDelete.value.open = true;
};

const closeModalDelete = () => {
    payload.id = null;
    modalDelete.value.open = false;
};

onMounted(() => {
    fetchTahun();
    fetchBulan();
    fetchVaksin();
    fetchPeramalan();
});
</script>

<template>
    <div class="w-full box-border">
        <div
            class="w-full relative overflow-x-auto shadow-md sm:rounded-lg border border-border bg-card text-card-foreground shadow"
        >
            <div
                class="box-border px-6 py-6 border-b border-border flex justify-between items-center"
            >
                <div>Histori Peramalan</div>

                <div class="flex items-center justify-center gap-2">
                    <div class="flex items-center">
                        <Button
                            @click.prevent="openModalTambah()"
                            variant="outline"
                            size="sm"
                        >
                            <FilePlus class="w-4 h-4 mr-3" />

                            <div class="text-xs">Buat Peramalan</div>
                        </Button>
                    </div>
                </div>
            </div>

            <div class="relative overflow-x-auto w-full">
                <LoadingTable
                    v-if="isLoading"
                    :thead="listthead"
                    :tbody="listthead"
                    :lengthtbody="5"
                />
                <template v-else>
                    <Table v-if="datalist.length > 0">
                        <TableHeader class="bg-muted/50">
                            <TableRow>
                                <TableHead
                                    v-for="item in listthead"
                                    class="capitalize"
                                    :class="
                                        item == 'MAD' ||
                                        item == 'MSE' ||
                                        item == 'MAPE' ||
                                        item == 'hasil'
                                            ? 'text-center'
                                            : ''
                                    "
                                    >{{ item }}</TableHead
                                >
                                <TableHead
                                    ><div class="sr-only">Aksi</div></TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, i) in datalist">
                                <TableCell
                                    >{{
                                        (pageInfo.current_page - 1) *
                                            pageInfo.per_page +
                                        1 +
                                        i
                                    }}.</TableCell
                                >
                                <TableCell>{{ item.vaksin }}</TableCell>
                                <TableCell>{{ item.namaBulan }}</TableCell>
                                <TableCell>{{ item.tahun }}</TableCell>
                                <TableCell>{{ item.alpha }}</TableCell>
                                <TableCell class="text-end">
                                    {{
                                        item.hasil.nextForecast.forecast.toFixed(
                                            2
                                        )
                                    }}
                                </TableCell>
                                <TableCell class="text-end">
                                    {{ item.hasil.average.mad.toFixed(2) }}
                                </TableCell>
                                <TableCell class="text-end">
                                    {{ item.hasil.average.mse.toFixed(2) }}
                                </TableCell>
                                <TableCell class="text-end">
                                    {{ item.hasil.average.mape.toFixed(2) }}
                                </TableCell>
                                <TableCell class="px-6 flex w-full">
                                    <div class="w-full flex justify-end gap-1">
                                        <button
                                            @click="
                                                router.push({
                                                    name: 'detailPeramalan',
                                                    params: { id: item.id },
                                                })
                                            "
                                            class="group relative bg-lime-400/20 hover:bg-lime-600 text-lime-500 hover:text-white rounded p-1 flex items-center gap-1"
                                        >
                                            <SquareGanttChart class="w-4 h-4" />
                                            <div
                                                class="bg-background/50 backdrop-blur rounded px-2 py-1 absolute -left-[15px] -top-8 w-[60px] text-[10px] border border-border hidden group-hover:block"
                                            >
                                                Detail
                                            </div>
                                        </button>
                                        <button
                                            @click.prevent="
                                                openModalDelete(item.id)
                                            "
                                            class="relative group bg-red-400/20 hover:bg-red-400 text-red-500 hover:text-white rounded p-1 flex items-center gap-1"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                            <div
                                                class="bg-background/50 backdrop-blur rounded px-2 py-1 absolute -left-[25%] -top-8 text-[10px] border border-border hidden group-hover:block"
                                            >
                                                Hapus
                                            </div>
                                        </button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <EmptyTable
                        v-else
                        :thead="listthead"
                        :tbody="listthead"
                        :lengthtbody="5"
                    />
                </template>

                <div
                    class="w-full border-t border-border box-border flex items-center justify-between px-6 py-3"
                >
                    <div
                        class="flex items-center justify-center space-x-2 text-xs"
                    >
                        <span>showing</span>
                        <div>
                            <DropdownMenu>
                                <DropdownMenuTrigger>
                                    <button
                                        class="relative border border-border hover:bg-accent rounded px-2 p-1 flex items-center gap-1"
                                    >
                                        {{ params.limit }}
                                        <ChevronsUpDown class="w-3 h-3" />
                                    </button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuItem
                                        v-for="item in [2, 10, 50, 100]"
                                        @click="changeLimit(item)"
                                        :class="
                                            item == params.limit
                                                ? 'bg-accent'
                                                : ''
                                        "
                                    >
                                        {{ item }}
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                        <div>from {{ pageInfo.from }}</div>
                        <div>to {{ pageInfo.to }}</div>
                    </div>
                    <div v-if="datalist.length > 0">
                        <Pagination
                            :total="pageInfo.total"
                            :currentPage="pageInfo.current_page"
                            :perPage="pageInfo.per_page"
                            @changePage="changePage"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal tambah -->
    <Modal ref="modalTambah">
        <template v-slot:modal-body>
            <div
                class="relative w-screen mx-6 lg:mx-0 lg:w-[640px] box-border overflow-x-hidden overflow-y-auto text-foreground bg-background border border-border rounded-lg"
            >
                <div
                    class="w-full box-border border-b border-border bg-background/50 backdrop-blur sticky top-0 flex items-center justify-between px-4 py-2"
                >
                    <div>Buat Peramalan</div>
                    <Button
                        @click.prevent="closeModalTambah()"
                        variant="ghost"
                        size="sm"
                    >
                        <X class="w-5 h-5" />
                    </Button>
                </div>
                <div class="w-full box-border px-3 py-6">
                    <form @submit.prevent="savePeramalan()" class="space-y-4">
                        <div class="w-full">
                            <Label class="block mb-2">Jenis Vaksin</Label>
                            <div class="w-full">
                                <DropdownSearch
                                    v-model="payload.idVaksin"
                                    :list="listvaksin"
                                    :textPlaceholder="'Pilih Jenis Vaksin'"
                                    :errorMessage="errors.idVaksin"
                                    @change="errors.idVaksin = null"
                                    class="z-[999999] w-full border border-border"
                                />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2">
                            <div class="w-full">
                                <Label class="block mb-2">Tahun</Label>
                                <div class="w-full">
                                    <Select
                                        v-model="payload.tahun"
                                        class="border-border"
                                    >
                                        <SelectTrigger>
                                            <SelectValue class="mr-1" />
                                        </SelectTrigger>
                                        <SelectContent class="z-[99999]">
                                            <SelectGroup>
                                                <SelectItem
                                                    v-for="item in listtahun"
                                                    :value="String(item.id)"
                                                >
                                                    {{ item.text }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <div class="w-full">
                                <Label class="block mb-2">Bulan</Label>
                                <div class="w-full">
                                    <Select
                                        v-model="payload.bulan"
                                        class="border-border"
                                    >
                                        <SelectTrigger>
                                            <SelectValue
                                                class="capitalize mr-1"
                                            />
                                        </SelectTrigger>
                                        <SelectContent class="z-[99999]">
                                            <SelectGroup>
                                                <SelectItem
                                                    v-for="item in listbulan"
                                                    :value="String(item.kode)"
                                                    class="capitalize"
                                                >
                                                    {{ item.text }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                        </div>

                        <div class="w-full">
                            <Label
                                class="block mb-px text-xs lg:text-sm flex-none"
                                >Jumlah Data Aktual</Label
                            >
                            <div class="w-full">
                                <Input
                                    @focus="errors.periode = null"
                                    type="number"
                                    v-model="payload.periode"
                                />
                                <span
                                    class="text-[10px] italic font-light text-red-400"
                                >
                                    {{ errors.periode }}
                                </span>
                            </div>
                            <div class="text-[10px] italic mt-1 text-gray-400">
                                *) Masukkan jumlah bulan yang menjadi data
                                aktual, misal 12.
                            </div>
                        </div>

                        <div class="w-full">
                            <Label class="block mb-px text-sm w-24 flex-none"
                                >Alpha</Label
                            >
                            <div class="w-full">
                                <Input
                                    @focus="errors.alpha = null"
                                    type="text"
                                    v-model="payload.alpha"
                                />
                                <span
                                    class="text-[10px] italic font-light text-red-400"
                                >
                                    {{ errors.alpha }}
                                </span>
                            </div>
                            <div class="text-[10px] italic mt-1 text-gray-400">
                                *) Masukkan nilai alpha misal 0.2.
                            </div>
                        </div>

                        <Button
                            type="submit"
                            variant="outline"
                            size="lg"
                            class="w-full"
                        >
                            <Save class="w-5 h-5 mr-3" />
                            <div class="text-sm">Submit</div>
                        </Button>
                    </form>
                </div>
            </div>
        </template>
    </Modal>
    <!-- end modal tambah -->

    <!-- modal edit -->
    <Modal ref="modalDelete">
        <template v-slot:modal-body>
            <div
                class="relative w-screen mx-6 lg:mx-0 lg:w-[640px] box-border overflow-x-hidden overflow-y-auto text-foreground bg-background border border-border rounded-lg"
            >
                <div class="w-full box-border px-8 py-6 pt-10">
                    <div class="w-full mb-6">
                        <div class="w-full flex space-x-5">
                            <div
                                class="flex-none w-20 h-20 rounded bg-red-700 text-white flex items-center justify-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-16 w-16"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    />
                                </svg>
                            </div>

                            <div class="flex-1">
                                <div class="mb-3 text-sm font-semibold">
                                    Apakah Anda yakin ingin menghapus data ?
                                </div>
                                <div class="mb-3 text-xs">
                                    Data yang dihapus beserta data yang
                                    berkaitan lainnya akan hilang secara
                                    permanen, apa anda ingin melanjutkan ?
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <Button
                            @click.prevent="closeModalDelete()"
                            variant="outline"
                            size="lg"
                            class="w-full hover:bg-emerald-500 hover:border-emerald-500 hover:text-white"
                        >
                            <!-- <Save class="w-5 h-5 mr-3" /> -->
                            <div class="text-sm">Tidak</div>
                        </Button>
                        <Button
                            @click.prevent="deletePeramalan()"
                            variant="outline"
                            size="lg"
                            class="w-full hover:bg-red-500 hover:border-red-500 hover:text-white"
                        >
                            <Trash2 class="w-5 h-5 mr-3" />
                            <div class="text-sm">Ya, Hapus Data</div>
                        </Button>
                    </div>
                </div>
            </div>
        </template>
    </Modal>
    <!-- end modal tambah -->
</template>
