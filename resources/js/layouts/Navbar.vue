<script setup>
import { ref, reactive, watch, onMounted } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRoute } from "vue-router";
import { useDark, useToggle, usePreferredDark } from "@vueuse/core";
import LogoutIcon from "@/components/icons/LogoutIcon.vue";
import DefaultUserImg from "@/assets/img/default_user.jpg";

const store = useAuthStore();
const route = useRoute();

const isDark = useDark();
const toggleDark = useToggle(isDark);

const userData = reactive(
    localStorage.getItem("USER") ? JSON.parse(localStorage.getItem("USER")) : {}
);

const logout = () => {
    store.logout();
};
</script>

<template>
    <div
        class="w-full h-16 bg-background/50 border-b border-border backdrop-blur-md flex items-center justify-between box-border px-4 absolute top-0 z-10"
    >
        <div class="w-full box-border flex justify-end">
            <div class="flex items-center gap-5">
                <div class="flex justify-center items-center group">
                    <div class="flex items-center truncate">
                        <span
                            class="hidden lg:block truncate text-xs font-semibold mr-2 capitalize"
                            >{{ userData.nama }} .</span
                        >

                        <div
                            class="w-8 h-8 rounded-full box-border overflow-hidden flex items-center justify-center mr-4"
                        >
                            <img
                                class="w-full"
                                :src="userData.foto ?? DefaultUserImg"
                                alt="User"
                            />
                        </div>

                        <div class="h-6 w-px bg-border mr-4"></div>

                        <button
                            @click="toggleDark()"
                            type="button"
                            class="whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10 flex items-center justify-center mr-4"
                            aria-label="Switch to light mode"
                        >
                            <svg
                                v-if="isDark"
                                viewBox="0 0 15 15"
                                width="1.2em"
                                height="1.2em"
                                class="w-[20px] h-5 text-foreground"
                            >
                                <path
                                    fill="currentColor"
                                    fill-rule="evenodd"
                                    d="M7.5 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2a.5.5 0 0 1 .5-.5M2.197 2.197a.5.5 0 0 1 .707 0L4.318 3.61a.5.5 0 0 1-.707.707L2.197 2.904a.5.5 0 0 1 0-.707M.5 7a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm1.697 5.803a.5.5 0 0 1 0-.707l1.414-1.414a.5.5 0 1 1 .707.707l-1.414 1.414a.5.5 0 0 1-.707 0M12.5 7a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm-1.818-2.682a.5.5 0 0 1 0-.707l1.414-1.414a.5.5 0 1 1 .707.707L11.39 4.318a.5.5 0 0 1-.707 0M8 12.5a.5.5 0 0 0-1 0v2a.5.5 0 0 0 1 0zm2.682-1.818a.5.5 0 0 1 .707 0l1.414 1.414a.5.5 0 1 1-.707.707l-1.414-1.414a.5.5 0 0 1 0-.707M5.5 7.5a2 2 0 1 1 4 0a2 2 0 0 1-4 0m2-3a3 3 0 1 0 0 6a3 3 0 0 0 0-6"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>

                            <svg
                                v-else
                                viewBox="0 0 15 15"
                                width="1.2em"
                                height="1.2em"
                                class="w-[20px] h-5 text-foreground"
                            >
                                <path
                                    fill="currentColor"
                                    fill-rule="evenodd"
                                    d="M2.9.5a.4.4 0 0 0-.8 0v.6h-.6a.4.4 0 1 0 0 .8h.6v.6a.4.4 0 1 0 .8 0v-.6h.6a.4.4 0 0 0 0-.8h-.6zm3 3a.4.4 0 1 0-.8 0v.6h-.6a.4.4 0 1 0 0 .8h.6v.6a.4.4 0 1 0 .8 0v-.6h.6a.4.4 0 0 0 0-.8h-.6zm-4 3a.4.4 0 1 0-.8 0v.6H.5a.4.4 0 1 0 0 .8h.6v.6a.4.4 0 0 0 .8 0v-.6h.6a.4.4 0 0 0 0-.8h-.6zM8.544.982l-.298-.04c-.213-.024-.34.224-.217.4A6.57 6.57 0 0 1 9.203 5.1a6.602 6.602 0 0 1-6.243 6.59c-.214.012-.333.264-.183.417a6.8 6.8 0 0 0 .21.206l.072.066l.26.226l.188.148l.121.09l.187.131l.176.115c.12.076.244.149.37.217l.264.135l.26.12l.303.122l.244.086a6.568 6.568 0 0 0 1.103.26l.317.04l.267.02a6.6 6.6 0 0 0 6.943-7.328l-.037-.277a6.557 6.557 0 0 0-.384-1.415l-.113-.268l-.077-.166l-.074-.148a6.602 6.602 0 0 0-.546-.883l-.153-.2l-.199-.24l-.163-.18l-.12-.124l-.16-.158l-.223-.2l-.32-.26l-.245-.177l-.292-.19l-.321-.186l-.328-.165l-.113-.052l-.24-.101l-.276-.104l-.252-.082l-.325-.09l-.265-.06zm1.86 4.318a7.578 7.578 0 0 0-.572-2.894a5.601 5.601 0 1 1-4.748 10.146a7.61 7.61 0 0 0 3.66-2.51a.749.749 0 0 0 1.355-.442a.75.75 0 0 0-.584-.732c.062-.116.122-.235.178-.355A1.25 1.25 0 1 0 10.35 6.2c.034-.295.052-.595.052-.9"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>

                            <!-- invisible group-hover:visible  -->
                            <!-- <div
                                class="invisible group-hover:visible fixed border border-gray-500 text-[#171C22] text-[10px] p-1 rounded"
                                style="transform: translate(-40px, 35px)"
                            >
                                {{
                                    isDark
                                        ? "Switch to light mode"
                                        : "Switch to dark mode"
                                }}
                            </div> -->
                        </button>

                        <button @click.prevent="logout()">
                            <LogoutIcon
                                class="w-6 h-6 fill-current rotate-180"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
