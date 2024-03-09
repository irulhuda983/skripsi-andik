import { ref, computed } from "vue";
import { defineStore } from "pinia";

export const useSettingStore = defineStore("setting", () => {
    const largeMenu = ref(localStorage.getItem("largeMenu") || true);

    const setLargeMenu = (val) => {
        largeMenu.value = val;
    };

    const collapseMenu = () => {
        largeMenu.value = !largeMenu.value;
        localStorage.setItem("largeMenu", largeMenu.value);
    };

    return { largeMenu, collapseMenu, setLargeMenu };
});
