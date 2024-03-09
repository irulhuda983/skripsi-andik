<script setup>
import { reactive, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import { useNotification } from "@kyvg/vue3-notification";
import EyeIcon from "@/components/icons/EyeIcon.vue";
import EyeOffIcon from "@/components/icons/EyeOffIcon.vue";
import ImgIlustrasi from "@/assets/img/lms.svg";
import SuntikIcon from "@/components/icons/SuntikIcon.vue";

const { login } = useAuthStore();
const router = useRouter();
const username = ref("");
const password = ref("");
const loading = ref(false);
const { notify } = useNotification();
const eyeVisible = ref(true);
const passwordType = ref("password");
const errors = reactive({
    username: null,
    password: null,
    login: null,
});
const flashError = ref(false);

const onLoginHandler = async () => {
    loading.value = true;
    try {
        await login({
            username: username.value,
            password: password.value,
        });
        // router.push({ path: "/" })
        location.reload();
    } catch (error) {
        if (error.status === 403) {
            errors.login =
                "Username atau password yang anda masukkan tidak sesuai.";
            // notify({
            //     text: "Username atau password salah",
            //     type: 'error',
            //     duration: 2000
            // })
            flashError.value = true;
        } else if (error.status == 422) {
            let err = error.data.errors;

            errors.username = err.username ? err.username[0] : null;
            errors.password = err.password ? err.password[0] : null;
        } else if (error.status == 400) {
            errors.login =
                "Username atau password yang anda masukkan tidak sesuai.";
            // errors.login = 'Akun anda sudah login di perangkat lain, hubungi administrator untuk mereset login anda.'
        } else {
            errors.login =
                "Username atau password yang anda masukkan tidak sesuai.";
            flashError.value = true;
        }
        loading.value = false;
    }
};

const togglePasswordInputVisibility = () => {
    eyeVisible.value
        ? (passwordType.value = "text")
        : (passwordType.value = "password");

    eyeVisible.value = !eyeVisible.value;
};
</script>

<template>
    <div
        class="w-screen h-screen box-border overflow-hidden bg-gradient-to-r from-[#e2e2e2] to-[#c9d6ff] flex items-center justify-center px-4"
    >
        <div
            class="bg-white rounded-[30px] relative overflow-hidden w-[768px] lg:h-[480px] flex flex-col-reverse lg:flex-row"
            style="box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35)"
            id="container"
        >
            <div class="w-full lg:w-1/2 lg:h-full py-10 lg:py-0">
                <form
                    method="post"
                    @submit.prevent="onLoginHandler"
                    class="w-full bg-white flex items-center justify-center flex-col px-[30px] h-full box-border"
                >
                    <h1 class="font-bold text-3xl lg:text-5xl mb-5">Sign In</h1>
                    <span class="text-[12px] mb-3 text-center"
                        >Silahkan Login Menggunakan Username dan Password
                        Anda.</span
                    >

                    <div
                        v-if="errors.login"
                        class="w-full rounded bg-red-300 text-red-600 box-border mb-3 p-3 relative"
                    >
                        <div
                            class="font-medium"
                            style="font-size: 10px; line-height: 16px"
                        >
                            {{ errors.login }}
                        </div>

                        <svg
                            @click.prevent="errors.login = null"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="cursor-pointer w-6 h-6 absolute right-1 top-2 hover:text-red-400"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>

                    <div class="w-full my-[8px]">
                        <input
                            @focus="errors.username = null"
                            type="text"
                            v-model="username"
                            placeholder="Username"
                            class="bg-[#eee] border-none px-[15px] py-[10px] text-[13px] w-full outline-none rounded-[8px]"
                        />
                        <div class="text-red-500 text-[10px] italic">
                            {{ errors.username }}
                        </div>
                    </div>
                    <div class="w-full my-[8px] relative">
                        <input
                            @focus="errors.password = null"
                            :type="passwordType"
                            v-model="password"
                            placeholder="Password"
                            class="bg-[#eee] border-none px-[15px] py-[10px] text-[13px] w-full outline-none rounded-[8px]"
                        />
                        <button
                            type="button"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 z-10"
                            @click.prevent="togglePasswordInputVisibility()"
                        >
                            <EyeIcon
                                v-show="eyeVisible"
                                class="h-5 w-5 text-gray-500"
                            />
                            <EyeOffIcon
                                v-show="!eyeVisible"
                                class="h-5 w-5 text-gray-500"
                            />
                        </button>
                        <div class="text-red-500 text-[10px] italic">
                            {{ errors.password }}
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="bg-[#512da8] text-white text-[12px] border border-transparent rounded-[8px] mt-[10px] uppercase px-[45px] py-[10px] mt-[10px] tracking-[0.5px]"
                    >
                        {{ loading ? "Memuat..." : "Sign In" }}
                    </button>
                </form>
            </div>
            <div
                class="w-full lg:w-1/2 lg:h-full overflow-hidden rounded-b-[70px] lg:rounded-b-none lg:rounded-tl-[150px] lg:rounded-bl-[100px] mb-5 lg:mb-0"
            >
                <div
                    class="bg-[#512da8] h-full text-white relative py-5 lg:py-0"
                    style="
                        background: linear-gradient(to right, #5c6bc0, #512da8);
                    "
                >
                    <div
                        class="w-full h-full flex items-center justify-center flex-col box-border px-[30px] text-center"
                    >
                        <div
                            class="flex lg:flex-col items-center justify-center mb-3"
                        >
                            <SuntikIcon
                                class="w-8 h-8 lg:w-16 lg:h-16 fill-current lg:mb-5 mr-5 lg:mr-0"
                            />
                            <h1 class="font-semibold text-xl lg:text-3xl">
                                Vaccine Stock
                            </h1>
                        </div>
                        <span class="text-[14px]"
                            >Sistem Peramalan Penggunaan Vaksin</span
                        >
                        <!-- <button class="hidden" id="register">Sign Up</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap");

#container {
    box-sizing: border-box;
    font-family: "Montserrat", sans-serif;
}

#container p {
    font-size: 14px;
    line-height: 20px;
    letter-spacing: 0.3px;
    margin: 20px 0;
}
</style>
