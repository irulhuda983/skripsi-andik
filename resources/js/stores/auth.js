import { ref, computed } from "vue";
import { defineStore } from "pinia";
// import axios from 'axios';

export const useAuthStore = defineStore("auth", () => {
    const user = ref(localStorage.getItem("USER") || null);
    const token = ref(localStorage.getItem("TOKEN") || null);
    const isAuthenticated = computed(() => !!(user.value && token.value));

    const setUser = (data) => {
        user.value = data;
        !user.value
            ? localStorage.removeItem("USER")
            : localStorage.setItem("USER", JSON.stringify(data));
    };

    const setToken = (data) => {
        token.value = data;
        !token.value
            ? localStorage.removeItem("TOKEN")
            : localStorage.setItem("TOKEN", data);
    };

    const getUser = async () => {
        const token = localStorage.getItem("TOKEN");
        if (!token) return;

        try {
            const { data } = await axiosInstance({
                url: "/account",
                method: "GET",
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            setUser(data.data);
        } catch (error) {
            setToken(null);
            setUser(null);
        }
    };

    const login = async (payload) => {
        try {
            const { data } = await axiosInstance({
                url: "/account/issue-token",
                method: "POST",
                data: payload,
            });
            await Promise.all([setToken(data.token), getUser()]);
        } catch (error) {
            setToken(null);
            setUser(null);

            if (error.response) {
                throw error.response;
            }
            throw error;
        }
    };

    const logout = () => {
        return axiosInstance({
            url: "/account/revoke-token",
            method: "POST",
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token.value}`,
            },
        }).then(() => {
            setToken(null);
            setUser(null);
            location.reload();
        });
    };

    return {
        user,
        token,
        isAuthenticated,
        getUser,
        login,
        logout,
    };
});
