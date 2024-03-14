<script setup>
import { ref, reactive, watch } from "vue";
import { RouterView } from "vue-router";
import { useSettingStore } from "@/stores/setting";
import { storeToRefs } from "pinia";
import Navbar from "@/layouts/Navbar.vue";
import Sidebar from "@/layouts/Sidebar.vue";

const settingStore = useSettingStore();

const { largeMenu } = storeToRefs(settingStore);
</script>

<template>
    <!-- bg-[#F2F2F2] text-[#424242] -->
    <div
        class="w-screen h-screen box-border overflow-x-hidden overflow-y-auto flex justify-between relative bg-background text-foreground"
    >
        <div
            class="w-full transition-all lg:h-full fixed lg:relative bottom-0 box-border flex-none z-[99999] lg:z-50"
            :class="largeMenu ? 'lg:w-60' : 'lg:w-24'"
        >
            <Sidebar />
        </div>

        <div
            class="relative w-full h-screen overflow-x-hidden overflow-y-auto flex-1"
        >
            <Navbar />
            <div
                class="w-full box-border overflow-x-hidden h-full pt-[80px] pb-[100px] lg:pb-[20px] px-4"
            >
                <RouterView />
            </div>
        </div>
    </div>
</template>
