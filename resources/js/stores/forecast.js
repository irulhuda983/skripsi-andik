import { ref, computed } from "vue";
import { defineStore } from "pinia";

export const useForecastStore = defineStore("forecast", () => {
    const singgleForecast = ref(null);
    const multiForecast = ref(null);

    const setForecastData = (val, typeForecast) => {
        if (typeForecast == "singgle") {
            singgleForecast.value = val;
        } else {
            multiForecast.value = val;
        }
    };

    return { singgleForecast, multiForecast, setForecastData };
});
