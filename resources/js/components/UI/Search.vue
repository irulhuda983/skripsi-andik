<script setup>
import { ref, watch, onMounted } from "vue";

const emit = defineEmits(["update:modelValue", "submit"]);
const props = defineProps(["modelValue"]);
const search = ref("");

onMounted(() => {
    search.value = props.modelValue;
});

watch(
    () => props.modelValue,
    (e) => {
        search.value = e;
    },
    {
        deep: true,
    }
);

watch(
    () => search.value,
    (e) => {
        emit("update:modelValue", e);
    },
    {
        deep: true,
    }
);
</script>
<template>
    <form
        @submit.prevent="emit('submit', search)"
        class="w-full relative text-base flex items-center justify-center"
    >
        <input
            type="text"
            class="w-full bg-transparent border border-border hover:border-primary py-2 w-full pl-3 pr-6 rounded-md focus:outline-none text-xs text-primary"
            v-model="search"
            placeholder="Search ....."
        />
        <button class="absolute text-neutral-500 right-3 hover:text-primary">
            <svg
                width="21"
                height="21"
                viewBox="0 0 21 21"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M9.5 18C14.1945 18 18 14.1945 18 9.5C18 4.8055 14.1945 1 9.5 1C4.8055 1 1 4.8055 1 9.5C1 14.1945 4.8055 18 9.5 18Z"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linejoin="round"
                />
                <path
                    d="M12.3285 6.1715C11.9575 5.79952 11.5166 5.50453 11.0312 5.30348C10.5458 5.10244 10.0254 4.9993 9.50001 5C8.97461 4.9993 8.45426 5.10244 7.96885 5.30348C7.48344 5.50453 7.04255 5.79952 6.67151 6.1715M15.611 15.611L19.8535 19.8535"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </button>
    </form>
</template>
