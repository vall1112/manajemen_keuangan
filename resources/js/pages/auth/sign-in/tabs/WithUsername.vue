<template>
    <VForm class="form w-100" @submit="submit" :validation-schema="login">
        <!--begin::Input group-->
        <div class="fv-row mb-10">
            <!--begin::Label-->
            <label class="form-label fs-6 fw-bold">Username</label>
            <!--end::Label-->

            <!--begin::Input-->
            <Field 
                tabindex="1" 
                class="form-control form-control-lg form-control-solid" 
                type="text" 
                name="username"
                autocomplete="off" 
                v-model="data.username" 
                placeholder="" 
            />
            <!--end::Input-->
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="username" />
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group password-->
        <div class="fv-row mb-5">
            <div class="d-flex flex-stack mb-2">
                <label class="form-label fw-bold fs-6 mb-0">Password</label>
            </div>
            <div class="position-relative mb-3">
                <Field 
                    tabindex="2" 
                    class="form-control form-control-lg form-control-solid" 
                    type="password" 
                    name="password"
                    v-model="data.password" 
                    autocomplete="off" 
                />
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2">
                    <i class="bi bi-eye-slash fs-2" @click="togglePassword"></i>
                </span>
            </div>
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="password" />
                </div>
            </div>
        </div>
        <!--end::Input group password-->

        <!--begin::Actions-->
        <div class="text-center">
            <button 
                tabindex="3" 
                type="submit" 
                ref="submitButton" 
                class="btn btn-lg btn-primary w-100 mb-5"
            >
                <span class="indicator-label">Login</span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
        <!--end::Actions-->
    </VForm>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import { blockBtn, unblockBtn } from "@/libs/utils";

export default defineComponent({
    setup() {
        const store = useAuthStore();
        const router = useRouter();
        const submitButton = ref(null);

        // Form validation
        const login = Yup.object().shape({
            username: Yup.string()
                .required("Harap masukkan Username")
                .min(3, "Username minimal 3 karakter")
                .max(50, "Username maksimal 50 karakter")
                .matches(/^[a-zA-Z0-9_-]+$/, "Username hanya boleh huruf, angka, _ atau -")
                .label("Username"),
            password: Yup.string()
                .min(8, 'Password minimal terdiri dari 8 karakter')
                .required('Harap masukkan password')
                .label("Password"),
        });

        return {
            login,
            submitButton,
            store,
            router,
        };
    },
    data() {
        return {
            data: {
                username: '',
                password: '',
            },
        };
    },
    methods: {
        submit() {
            blockBtn(this.submitButton);

            axios.post("/auth/login/username", { ...this.data, type: "username" })
                .then(res => {
                    this.store.setAuth(res.data.user, res.data.token);
                    this.router.push("/dashboard");
                })
                .catch(error => {
                    toast.error(error.response?.data?.message || "Terjadi kesalahan");
                })
                .finally(() => {
                    unblockBtn(this.submitButton);
                });
        },
        togglePassword(ev: Event) {
            const input = document.querySelector<HTMLInputElement>("[name=password]");
            const icon = ev.target as HTMLElement;

            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                icon.classList.add("bi-eye");
                icon.classList.remove("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        },
    },
});
</script>
