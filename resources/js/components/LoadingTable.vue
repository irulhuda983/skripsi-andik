<script setup>
import { onMounted } from "vue";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/UI/table";
import { FilePlus } from "lucide-vue-next";
const props = defineProps(["thead", "tbody", "lengthtbody"]);
</script>

<template>
    <Table>
        <TableHeader class="bg-muted/50" v-if="thead.length > 0">
            <TableRow>
                <TableHead v-for="item in thead"
                    ><div class="bg-muted text-muted rounded">
                        {{ item }}
                    </div></TableHead
                >
                <TableHead><div class="sr-only">Aksi</div></TableHead>
            </TableRow>
        </TableHeader>
        <TableBody v-if="tbody.length > 0">
            <TableRow v-for="i in lengthtbody">
                <TableCell v-for="item in tbody">
                    <div class="bg-muted text-muted rounded">
                        {{ item }}
                    </div>
                </TableCell>
                <TableCell class="px-6 flex w-full">
                    <div class="w-full flex justify-end gap-1">
                        <button
                            v-for="i in 3"
                            class="relative group bg-muted text-muted rounded p-1 flex items-center gap-1"
                        >
                            <FilePlus class="w-4 h-4" />
                        </button>
                    </div>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
