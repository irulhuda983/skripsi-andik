<template>
    <TransitionRoot as="template" :show="open">
        <Dialog
            as="div"
            class="w-screen h-screen fixed top-0 bottom-0 left-0 right-0 z-[9999] box-border overflow-hidden"
        >
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed box-border inset-0 bg-background/10 backdrop-blur transition-opacity"
                />
            </TransitionChild>
            <div class="fixed z-50 inset-0 box-border overflow-hidden">
                <div
                    class="flex box-border items-end sm:items-center justify-center items-center h-screen p-4 text-center sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative text-left overflow-hidden shadow-xl transform transition-all h-screen box-border py-6 flex items-center justify-center"
                        >
                            <slot name="modal-body"></slot>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

export default {
    components: {
        Dialog,
        DialogPanel,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
    },
    data() {
        return {
            open: false,
        };
    },
    methods: {
        close() {
            console.log("close");
            this.open = false;
        },
    },
};
</script>
