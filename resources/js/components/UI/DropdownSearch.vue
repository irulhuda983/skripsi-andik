<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { Check, ChevronsUpDown } from "lucide-vue-next";
import { cn } from "@/lib/utils";
import { Button } from "@/components/UI/button";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/components/UI/command";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/UI/popover";
import Input from "../UI/input/Input.vue";

const open = ref(false);
const value = ref(0);
const search = ref("");
const emit = defineEmits(["update:modelValue", "change"]);
const props = defineProps([
    "modelValue",
    "list",
    "textPlaceholder",
    "errorMessage",
]);

let filteredList = computed(() =>
    search.value == ""
        ? props?.list
        : props?.list.filter((obj) =>
              obj.text
                  .toLowerCase()
                  .replace(/\s+/g, "")
                  .includes(search.value.toLowerCase().replace(/\s+/g, ""))
          )
);

watch(
    () => value.value,
    (e) => {
        emit("update:modelValue", e);
    }
);
onMounted(() => {
    value.value = props?.modelValue;
});
</script>
<template>
    <Popover class="w-full z-[99999] border border-border" v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="justify-between w-full"
                @click="emit('change', errorMessage)"
            >
                {{
                    value
                        ? list.find((framework) => framework.id == value)?.text
                        : textPlaceholder
                }}
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
            <span class="text-[10px] italic font-light text-red-400">{{
                errorMessage
            }}</span>
        </PopoverTrigger>
        <PopoverContent class="w-[280px] lg:w-[620px] p-0 z-[99999]">
            <Command>
                <div class="p-2">
                    <Input
                        class="h-9 border-border focus-visible:ring-border focus:outline-none focus:ring-none focus:border-none"
                        placeholder="Search..."
                        v-model="search"
                    />
                </div>
                <CommandEmpty>No data found.</CommandEmpty>
                <CommandList>
                    <CommandGroup>
                        <CommandItem
                            v-for="framework in filteredList"
                            :key="framework.id"
                            :value="framework.id"
                            @select="
                                (ev) => {
                                    value = ev.detail.value;
                                    open = false;
                                }
                            "
                        >
                            {{ framework.text }}
                            <Check
                                :class="
                                    cn(
                                        'ml-auto h-4 w-4',
                                        value == framework.id
                                            ? 'opacity-100'
                                            : 'opacity-0'
                                    )
                                "
                            />
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
