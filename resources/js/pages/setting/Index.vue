<script setup>
import { ref, reactive, onMounted, watch } from "vue";
import { useFormatJsonToFormData } from "@/composables/jsonformater";
import { useNotification } from "@kyvg/vue3-notification";

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

const userData = ref(
    localStorage.getItem("USER") ? JSON.parse(localStorage.getItem("USER")) : {}
);
const { notify } = useNotification();
const fotoRef = ref();
const errors = reactive({
    nama: null,
    username: null,
    password: null,
    foto: null,
});

const payload = reactive({
    nama: userData.value.nama,
    username: userData.value.username,
    password: null,
    foto: userData.value.foto,
});

const readImage = () => {
    errors.foto = null;
    const files = fotoRef.value.files[0];
    const sizeFile = files.size;
    const tipeFile = files.type;

    const arrType = [
        "image/jpg",
        "image/jpeg",
        "image/svg+xml",
        "image/gif",
        "image/png",
    ];
    const maxFile = 2 * 1048576;

    if (!arrType.includes(tipeFile)) {
        errors.foto = "Type File must be jpg, jpeg, png, svg or gif";
        return;
    }

    if (sizeFile > maxFile) {
        errors.foto = "file size cannot be larger than 2 mb";
        return;
    }
    payload.foto = URL.createObjectURL(files);
};

const updateProfil = async () => {
    try {
        const formData = new FormData();
        formData.append("nama", payload.nama ?? "");
        formData.append("username", payload.username ?? "");
        formData.append("password", payload.password ?? "");
        formData.append("gender", "l");
        if (fotoRef.value.files[0] !== undefined) {
            formData.append("foto", fotoRef.value.files[0]);
        }

        const { data } = await axiosInstance({
            url: `/account/update`,
            method: "POST",
            headers: {
                "Content-Type": "multipart/form-data",
            },
            data: formData,
        });

        if (data.token) {
            localStorage.removeItem("TOKEN");
            localStorage.setItem("TOKEN", data.token);
        }

        if (data.userInfo) {
            localStorage.removeItem("USER");
            localStorage.setItem("USER", JSON.stringify(data.userInfo));
        }

        notify({
            text: "Berhasil update profil",
            type: "success",
            duration: 2000,
        });

        location.reload();
    } catch (e) {
        if (e.response.status == 401) {
            localStorage.removeItem("TOKEN");
            location.reload();
        } else if (e.response.status == 422) {
            const err = e.response.data.errors;
            errors.nama = err.nama ? err.nama[0] : null;
            errors.username = err.username ? err.username[0] : null;
            errors.password = err.password ? err.password[0] : null;
            errors.foto = err.foto ? err.foto[0] : null;
        } else {
            notify({
                text: "Faliled to add, Server is Maintenent",
                type: "error",
                duration: 2000,
            });
        }
    }
};
</script>

<template>
    <div class="w-full box-border">
        <div
            class="w-full relative overflow-x-auto shadow-md sm:rounded-lg border border-border bg-card text-card-foreground shadow"
        >
            <div
                class="box-border px-6 py-6 border-b border-border flex justify-between items-center"
            >
                <div>Update Profil</div>
            </div>

            <div class="relative overflow-x-auto w-full">
                <div class="w-full box-border px-3 py-6">
                    <form @submit.prevent="updateProfil()">
                        <div
                            class="w-full flex flex-col lg:flex-row gap-2 mb-5"
                        >
                            <div
                                class="w-full lg:w-1/2 flex items-center justify-center"
                            >
                                <div class="mb-6 w-2/3">
                                    <div
                                        class="flex items-center justify-center w-full"
                                    >
                                        <label
                                            for="foto-ref"
                                            class="flex flex-col items-center justify-center box-border w-full h-64 border-2 border-dashed rounded-lg cursor-pointer overflow-hidden"
                                            :class="
                                                errors.foto
                                                    ? 'border-red-300'
                                                    : 'border-border'
                                            "
                                        >
                                            <div v-if="payload.foto">
                                                <img
                                                    :src="payload.foto"
                                                    alt=""
                                                    loading="lazy"
                                                    class="w-full object-cover object-center"
                                                />
                                            </div>
                                            <div
                                                v-else
                                                class="flex flex-col items-center justify-center pt-5 pb-6"
                                            >
                                                <svg
                                                    class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                    aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 20 16"
                                                >
                                                    <path
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
                                                    />
                                                </svg>
                                                <p
                                                    class="mb-2 text-sm text-gray-500 dark:text-gray-400"
                                                >
                                                    <span class="font-semibold"
                                                        >Click to upload</span
                                                    >
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    SVG, PNG, JPG or GIF (MAX. 2
                                                    mb)
                                                </p>
                                            </div>
                                            <input
                                                id="foto-ref"
                                                ref="fotoRef"
                                                type="file"
                                                class="sr-only"
                                                @change="readImage()"
                                            />
                                        </label>
                                    </div>
                                    <div
                                        v-if="errors.foto"
                                        class="text-red-500 italic text-xs mt-1"
                                    >
                                        {{ errors.foto }}
                                    </div>
                                </div>
                            </div>
                            <div class="w-full lg:w-1/2 space-y-4">
                                <div class="w-full">
                                    <Label class="block mb-px text-sm flex-none"
                                        >Nama Lengkap</Label
                                    >
                                    <div class="w-full">
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
                                </div>

                                <div class="w-full">
                                    <Label class="block mb-px text-sm flex-none"
                                        >Username</Label
                                    >
                                    <div class="w-full">
                                        <Input
                                            @focus="errors.username = null"
                                            type="text"
                                            v-model="payload.username"
                                        />
                                        <span
                                            class="text-[10px] italic font-light text-red-400"
                                        >
                                            {{ errors.username }}
                                        </span>
                                    </div>
                                </div>

                                <div class="w-full">
                                    <Label class="block mb-px text-sm flex-none"
                                        >Password</Label
                                    >
                                    <div class="w-full">
                                        <Input
                                            @focus="errors.password = null"
                                            type="password"
                                            v-model="payload.password"
                                        />
                                        <span
                                            class="text-[10px] italic font-light text-red-400"
                                        >
                                            {{ errors.password }}
                                        </span>
                                    </div>
                                </div>
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
        </div>
    </div>
</template>
