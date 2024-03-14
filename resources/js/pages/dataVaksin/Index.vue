<script setup>
import { ref, reactive, onMounted, watch } from "vue";
import { useFormatJsonToFormData } from "@/composables/jsonformater";
import { useNotification } from "@kyvg/vue3-notification";
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

const { notify } = useNotification();
const isOpenAdd = ref(false);
const modalTambah = ref();
const modalEdit = ref();
const modalEditNilai = ref();
const modalDelete = ref();
const isLoading = ref(false);
const datalist = ref([]);
const listtahun = ref([]);
const listthead = ref([
    "no.",
    "nama vaksin",
    "jan",
    "feb",
    "mar",
    "apr",
    "mei",
    "jun",
    "jul",
    "ags",
    "sep",
    "okt",
    "nov",
    "des",
]);

const payloadTransaksi = ref([]);
const transaksi = ref([]);

const payload = reactive({
    id: null,
    kode: null,
    nama: null,
    tahun: null,
});

const errors = reactive({
    id: null,
    kode: null,
    nama: null,
    tahun: null,
});

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

const fetchData = async () => {
    isLoading.value = true;
    try {
        const { data } = await axiosInstance({
            url: `/vaksin`,
            method: "GET",
            params: params,
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

const fetchDataById = async (id) => {
    try {
        const { data } = await axiosInstance({
            url: `/vaksin/${id}/show`,
            method: "GET",
        });

        payload.id = id;
        payload.kode = data.data.kode;
        payload.nama = data.data.nama;
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

const fetchDataPerBulan = async (id) => {
    try {
        const { data } = await axiosInstance({
            url: `/vaksin/${id}/list-per-bulan`,
            method: "GET",
            params: { tahun: params.tahun },
        });

        payload.id = id;
        payload.kode = data.dataVaksin.kode;
        payload.nama = data.dataVaksin.nama;
        payload.tahun = data.tahun;

        payloadTransaksi.value = data.dataPerBulan;
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

const saveVaksin = async () => {
    try {
        const { data } = await axiosInstance({
            url: "/vaksin",
            method: "POST",
            data: payload,
        });
        fetchData();
        closeModalTambah();
        notify({
            text: "Berhasil tambah vaksin",
            type: "success",
            duration: 2000,
        });
    } catch (e) {
        if (e.response.status == 401) {
            localStorage.removeItem("TOKEN");
            location.reload();
        } else if (e.response.status == 422) {
            const err = e.response.data.errors;
            errors.kode = err.kode ? err.kode[0] : null;
            errors.nama = err.nama ? err.nama[0] : null;
        } else {
            closeModalTambah();
            notify({
                text: "Faliled to add, Server is Maintenent",
                type: "error",
                duration: 2000,
            });
        }
    }
};

const updateVaksin = async () => {
    try {
        const { data } = await axiosInstance({
            url: `/vaksin/${payload.id}/update`,
            method: "POST",
            data: payload,
        });
        fetchData();
        closeModalEdit();
        notify({
            text: "Berhasil update vaksin",
            type: "success",
            duration: 2000,
        });
    } catch (e) {
        if (e.response.status == 401) {
            localStorage.removeItem("TOKEN");
            location.reload();
        } else if (e.response.status == 422) {
            const err = e.response.data.errors;
            errors.kode = err.kode ? err.kode[0] : null;
            errors.nama = err.nama ? err.nama[0] : null;
        } else {
            closeModalEdit();
            notify({
                text: "Faliled to add, Server is Maintenent",
                type: "error",
                duration: 2000,
            });
        }
    }
};

const updateNilai = async () => {
    try {
        let dataSend = {
            tahun: payload.tahun,
            transaksi: JSON.stringify(payloadTransaksi.value),
        };

        const { data } = await axiosInstance({
            url: `/vaksin/${payload.id}/update-transaksi`,
            method: "POST",
            data: dataSend,
        });

        fetchData();
        closeModalEditNilai();
        notify({
            text: "Berhasil update nilai vaksin",
            type: "success",
            duration: 2000,
        });
    } catch (e) {
        if (e.response.status == 401) {
            localStorage.removeItem("TOKEN");
            location.reload();
        } else if (e.response.status == 422) {
            const err = e.response.data.errors;
            errors.kode = err.kode ? err.kode[0] : null;
            errors.nama = err.nama ? err.nama[0] : null;
        } else {
            closeModalEdit();
            notify({
                text: "Faliled to add, Server is Maintenent",
                type: "error",
                duration: 2000,
            });
        }
    }
};

const deleteVaksin = async () => {
    try {
        const { data } = await axiosInstance({
            url: `/vaksin/${payload.id}/delete`,
            method: "DELETE",
        });
        fetchData();
        closeModalDelete();
        notify({
            text: "Berhasil hapus vaksin",
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

// open modal tambah
const openModalTambah = () => {
    modalTambah.value.open = true;
};

// open modal tambah
const closeModalTambah = () => {
    resetPayloadAndError();
    modalTambah.value.open = false;
};

// open modal edit
const openModalEdit = (id) => {
    fetchDataById(id);
    modalEdit.value.open = true;
};

// open modal tambah
const closeModalEdit = () => {
    resetPayloadAndError();
    modalEdit.value.open = false;
};

// open modal edit
const openModalEditNilai = (id) => {
    fetchDataPerBulan(id);
    modalEditNilai.value.open = true;
};

// open modal tambah
const closeModalEditNilai = () => {
    resetPayloadAndError();
    modalEditNilai.value.open = false;
};

// open modal edit
const openModalDelete = (id) => {
    fetchDataById(id);
    modalDelete.value.open = true;
};

// open modal tambah
const closeModalDelete = () => {
    resetPayloadAndError();
    modalDelete.value.open = false;
};

const resetPayloadAndError = () => {
    payload.id = null;
    payload.kode = null;
    payload.nama = null;
    payload.tahun = null;

    errors.id = null;
    errors.kode = null;
    errors.nama = null;
    errors.tahun = null;

    payloadTransaksi.value = [];
};

const changePage = (page) => {
    params.page = page;
    fetchData();
};

const changeLimit = (limit) => {
    params.limit = limit;
    fetchData();
};

const changeTahun = (tahun) => {
    params.tahun = tahun;
    fetchData();
};

onMounted(() => {
    fetchTahun();
    fetchData();
});
</script>

<template>
    <div class="w-full box-border">
        <div
            class="w-full relative overflow-x-auto shadow-md sm:rounded-lg border border-border bg-card text-card-foreground shadow"
        >
            <div
                class="w-full box-border px-6 py-6 border-b border-border flex flex-col lg:flex-row lg:justify-between lg:items-center gap-2"
            >
                <div class="w-full lg:w-auto flex items-center">
                    <Button
                        @click.prevent="openModalTambah()"
                        variant="outline"
                        size="sm"
                    >
                        <FilePlus class="w-4 h-4 mr-3" />

                        <div class="text-xs">Tambah Data</div>
                    </Button>
                </div>

                <div
                    class="flex items-center justify-center gap-2 flex-col lg:flex-row"
                >
                    <div class="w-full lg:w-auto">
                        <DropdownMenu>
                            <DropdownMenuTrigger>
                                <button
                                    class="relative border border-border hover:bg-accent rounded text-xs px-2 p-2 flex items-center gap-1"
                                >
                                    {{ params.tahun }}
                                    <ChevronsUpDown class="w-3 h-3" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuItem
                                    v-for="item in listtahun"
                                    @click="changeTahun(item.id)"
                                    :class="
                                        item.id == params.tahun
                                            ? 'bg-accent'
                                            : ''
                                    "
                                >
                                    {{ item.text }}
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                    <Search
                        v-model="params.search"
                        @submit="fetchData()"
                        class="w-full lg:w-[360px]"
                    />
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
                                <TableCell>{{ item.nama }}</TableCell>
                                <TableCell>{{ item.januari }}</TableCell>
                                <TableCell>{{ item.februari }}</TableCell>
                                <TableCell>{{ item.maret }}</TableCell>
                                <TableCell>{{ item.april }}</TableCell>
                                <TableCell>{{ item.mei }}</TableCell>
                                <TableCell>{{ item.juni }}</TableCell>
                                <TableCell>{{ item.juli }}</TableCell>
                                <TableCell>{{ item.agustus }}</TableCell>
                                <TableCell>{{ item.september }}</TableCell>
                                <TableCell>{{ item.oktober }}</TableCell>
                                <TableCell>{{ item.november }}</TableCell>
                                <TableCell>{{ item.desember }}</TableCell>
                                <TableCell class="px-6 flex w-full">
                                    <div class="w-full flex justify-end gap-1">
                                        <button
                                            @click.prevent="
                                                openModalEditNilai(item.id)
                                            "
                                            class="group relative bg-lime-400/20 hover:bg-lime-600 text-lime-500 hover:text-white rounded p-1 flex items-center gap-1"
                                        >
                                            <SquareGanttChart class="w-4 h-4" />
                                            <div
                                                class="bg-background/50 backdrop-blur rounded px-2 py-1 absolute -left-[15px] -top-8 w-[60px] text-[10px] border border-border hidden group-hover:block"
                                            >
                                                Edit Nilai
                                            </div>
                                        </button>
                                        <button
                                            @click.prevent="
                                                openModalEdit(item.id)
                                            "
                                            class="group relative bg-amber-400/20 hover:bg-amber-400 text-amber-500 hover:text-white rounded p-1 flex items-center gap-1"
                                        >
                                            <FilePenLine class="w-4 h-4" />
                                            <div
                                                class="bg-background/50 backdrop-blur rounded px-2 py-1 absolute -left-[25%] -top-8 text-[10px] border border-border hidden group-hover:block"
                                            >
                                                Edit
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
                    <div>
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
                class="relative w-screen mx-4 lg:mx-0 lg:w-[640px] box-border overflow-x-hidden overflow-y-auto text-foreground bg-background border border-border rounded-lg"
            >
                <div
                    class="w-full box-border border-b border-border bg-background/50 backdrop-blur sticky top-0 flex items-center justify-between px-4 py-2"
                >
                    <div>Tambah Data Vaksin</div>
                    <Button
                        @click.prevent="closeModalTambah()"
                        variant="ghost"
                        size="sm"
                    >
                        <X class="w-5 h-5" />
                    </Button>
                </div>
                <div class="w-full box-border px-3 py-6">
                    <form @submit.prevent="saveVaksin()" class="space-y-4">
                        <div class="w-full">
                            <Label class="block mb-px text-sm"
                                >Kode Vaksin</Label
                            >
                            <Input
                                @focus="errors.kode = null"
                                type="text"
                                v-model="payload.kode"
                            />
                            <span
                                class="text-[10px] italic font-light text-red-400"
                            >
                                {{ errors.kode }}
                            </span>
                        </div>
                        <div class="w-full">
                            <Label class="block mb-px text-sm"
                                >Nama Vaksin</Label
                            >
                            <Input
                                @focus="errors.nama = null"
                                type="text"
                                v-model="payload.nama"
                            />
                            <span
                                class="text-[10px] italic font-light text-red-400"
                            >
                                {{ errors.nama }}
                            </span>
                        </div>

                        <Button
                            type="submit"
                            variant="outline"
                            size="lg"
                            class="w-full"
                        >
                            <Save class="w-5 h-5 mr-3" />
                            <div class="text-sm">Simpan</div>
                        </Button>
                    </form>
                </div>
            </div>
        </template>
    </Modal>
    <!-- end modal tambah -->

    <!-- modal edit -->
    <Modal ref="modalEdit">
        <template v-slot:modal-body>
            <div
                class="relative w-screen mx-4 lg:mx-0 lg:w-[640px] box-border overflow-x-hidden overflow-y-auto text-foreground bg-background border border-border rounded-lg"
            >
                <div
                    class="w-full box-border border-b border-border bg-background/50 backdrop-blur sticky top-0 flex items-center justify-between px-4 py-2"
                >
                    <div>Edit Data Vaksin</div>
                    <Button
                        @click.prevent="closeModalEdit()"
                        variant="ghost"
                        size="sm"
                    >
                        <X class="w-5 h-5" />
                    </Button>
                </div>
                <div class="w-full box-border px-3 py-6">
                    <form @submit.prevent="updateVaksin()" class="space-y-4">
                        <div class="w-full">
                            <Label class="block mb-px text-sm"
                                >Kode Vaksin</Label
                            >
                            <Input
                                @focus="errors.kode = null"
                                type="text"
                                v-model="payload.kode"
                            />
                            <span
                                class="text-[10px] italic font-light text-red-400"
                            >
                                {{ errors.kode }}
                            </span>
                        </div>
                        <div class="w-full">
                            <Label class="block mb-px text-sm"
                                >Nama Vaksin</Label
                            >
                            <Input
                                @focus="errors.nama = null"
                                type="text"
                                v-model="payload.nama"
                            />
                            <span
                                class="text-[10px] italic font-light text-red-400"
                            >
                                {{ errors.nama }}
                            </span>
                        </div>

                        <Button
                            type="submit"
                            variant="outline"
                            size="lg"
                            class="w-full"
                        >
                            <Save class="w-5 h-5 mr-3" />
                            <div class="text-sm">Simpan</div>
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
                class="relative w-screen mx-4 lg:mx-0 lg:w-[640px] box-border overflow-x-hidden overflow-y-auto text-foreground bg-background border border-border rounded-lg"
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
                                    Apakah Anda yakin ingin menghapus data
                                    {{ payload.nama }} ?
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
                            @click.prevent="deleteVaksin()"
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

    <!-- modal edit nilai -->
    <Modal ref="modalEditNilai">
        <template v-slot:modal-body>
            <div
                class="relative w-screen h-full mx-4 lg:mx-0 lg:w-[640px] box-border overflow-x-hidden overflow-y-auto text-foreground bg-background border border-border rounded-lg"
            >
                <div
                    class="w-full box-border border-b border-border bg-background/50 backdrop-blur sticky top-0 flex items-center justify-between px-4 py-2"
                >
                    <div>Update Jumlah Vaksin</div>
                    <Button
                        @click.prevent="closeModalEditNilai()"
                        variant="ghost"
                        size="sm"
                    >
                        <X class="w-5 h-5" />
                    </Button>
                </div>

                <div
                    class="w-full box-border border-b border-border flex items-center px-4 py-2 gap-3"
                >
                    <div
                        class="flex items-center gap-3 text-xs rounded px-3 py-2 border border-border bg-accent/50"
                    >
                        <div class="font-semibold">Vaksin :</div>
                        <div>{{ payload.nama }}</div>
                    </div>
                    <div
                        class="flex items-center gap-3 text-xs rounded px-3 py-2 border border-border bg-accent/50"
                    >
                        <div class="font-semibold">Tahun :</div>
                        <div>{{ payload.tahun }}</div>
                    </div>
                </div>

                <div class="w-full box-border px-3 py-6">
                    <div class="mb-2 text-sm">Bulan</div>
                    <form @submit.prevent="updateNilai()" class="space-y-4">
                        <div
                            class="w-full grid grid-rows-6 grid-flow-col gap-2"
                        >
                            <div
                                class="w-full border border-border flex items-center box-border h-10"
                                v-for="item in payloadTransaksi"
                            >
                                <label
                                    class="box-border w-[80px] lg:w-[120px] flex-none h-full block mb-px text-xs lg:text-sm flex items-center px-2 lg:px-4 bg-accent/50 capitalize font-normal"
                                    >{{ item.namaBulan }}</label
                                >
                                <input
                                    type="text"
                                    v-model="item.qty"
                                    class="bg-transparent w-full h-full flex-1 box-border outline-none px-4 py-2 text-sm"
                                />
                            </div>
                        </div>

                        <Button
                            type="submit"
                            variant="outline"
                            size="lg"
                            class="w-full"
                        >
                            <Save class="w-5 h-5 mr-3" />
                            <div class="text-sm">Simpan</div>
                        </Button>
                    </form>
                </div>
            </div>
        </template>
    </Modal>
    <!-- end modal edit nilai -->
</template>
