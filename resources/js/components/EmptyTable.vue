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
                    ><div class="capitalize">
                        {{ item }}
                    </div></TableHead
                >
                <TableHead><div class="sr-only">Aksi</div></TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow>
                <TableCell :colspan="thead.length + 1">
                    <div class="italic font-semibold text-center py-4">
                        Tidak ada data tersedia.
                    </div>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
