<script setup>
import { useRouter, useRoute } from "vue-router";
import { useSettingStore } from "@/stores/setting";
import { storeToRefs } from "pinia";
import SuntikIcon from "@/components/icons/SuntikIcon.vue";

import {
    LayoutDashboard,
    Archive,
    FilePieChart,
    Settings,
} from "lucide-vue-next";

const route = useRoute();
const settingStore = useSettingStore();
const { collapseMenu } = useSettingStore();
const { largeMenu } = storeToRefs(settingStore);
</script>

<template>
    <!-- bg-[#11143D] -->
    <div
        class="hidden lg:flex w-full h-full box-boder flex-row lg:flex-col relative bg-background/80 border-r border-border text-sm"
    >
        <div
            class="w-full box-border h-16 px-6 py-4 relative flex items-center justify-between"
        >
            <div
                class="w-full box-border flex items-center gap-3 border-b border-border py-3"
                :class="largeMenu ? 'justify-center' : ''"
            >
                <div
                    class="flex-none bg-secondary box-border rounded w-12 h-10 flex items-center justify-center text-cyan-600 dark:text-white"
                >
                    <SuntikIcon class="w-6 h-6 fill-current" />
                </div>
                <!-- <div
                    class="font-bold text-sm w-full box-border pr-3 tracking-wide"
                >
                    Sistem Pengadaan<br />Stock Vaksin
                </div> -->
                <div
                    class="font-bold w-full box-border pr-3 tracking-wide"
                    :class="largeMenu ? 'block' : 'hidden'"
                >
                    Vaccine Stock
                </div>
            </div>
            <button
                @click.prevent="collapseMenu"
                class="absolute -right-4 z-20 bg-secondary border border-border flex items-center justify-center w-8 h-8 rounded-lg shadow-lg"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="w-5 h-5"
                    :class="largeMenu ? '' : 'rotate-180'"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5"
                    />
                </svg>
            </button>
        </div>

        <div class="w-full h-full px-6 mt-6">
            <div class="w-full box-border">
                <!-- bg-[#556FF6] -->
                <router-link
                    :to="{ name: 'dashboard' }"
                    class="w-full box-border text px-3 py-2 flex items-center gap-3 rounded-[5px] mb-2"
                    :class="
                        route.meta.menu == 'dashboard'
                            ? 'bg-accent text-foreground'
                            : 'text-muted-foreground hover:text-foreground'
                    "
                >
                    <LayoutDashboard class="w-5 h-5" />
                    <div
                        class="font-medium"
                        :class="largeMenu ? 'block' : 'hidden'"
                    >
                        Dashboard
                    </div>
                </router-link>
                <router-link
                    :to="{ name: 'dataVaksin' }"
                    class="w-full box-border text px-3 py-2 flex items-center gap-3 rounded-[5px] mb-2"
                    :class="
                        route.meta.menu == 'dataVaksin'
                            ? 'bg-accent text-foreground'
                            : 'text-muted-foreground hover:text-foreground'
                    "
                >
                    <Archive class="w-5 h-5" />
                    <div
                        class="font-medium text-sm"
                        :class="largeMenu ? 'block' : 'hidden'"
                    >
                        Data Vaksin
                    </div>
                </router-link>
                <router-link
                    :to="{ name: 'peramalan' }"
                    class="w-full box-border text px-3 py-2 flex items-center gap-3 rounded-[5px] mb-2"
                    :class="
                        route.meta.menu == 'peramalan'
                            ? 'bg-accent text-foreground'
                            : 'text-muted-foreground hover:text-foreground'
                    "
                >
                    <FilePieChart class="w-5 h-5" />
                    <div
                        class="font-medium text-sm"
                        :class="largeMenu ? 'block' : 'hidden'"
                    >
                        Peramalan
                    </div>
                </router-link>
                <router-link
                    :to="{ name: 'setting' }"
                    class="w-full box-border text px-3 py-2 flex items-center gap-3 rounded-[5px] mb-2"
                    :class="
                        route.meta.menu == 'setting'
                            ? 'bg-accent text-foreground'
                            : 'text-muted-foreground hover:text-foreground'
                    "
                >
                    <Settings class="w-5 h-5" />
                    <div
                        class="font-medium text-sm"
                        :class="largeMenu ? 'block' : 'hidden'"
                    >
                        Setting
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>
