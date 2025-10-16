<template>
    <VForm class="form w-100" @submit="submit" :validation-schema="login">
        <!--begin::Input group-->
        <div class="fv-row mb-10">
            <!--begin::Label-->
            <label class="form-label fs-6 fw-bold">Username / Email</label>
            <!--end::Label-->

            <!--begin::Input-->
            <Field tabindex="1" class="form-control form-control-lg form-control-solid" type="text" name="login"
                autocomplete="off" v-model="data.login" placeholder="Masukkan Username atau Email" />
            <!--end::Input-->
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="login" />
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
                <Field tabindex="2" class="form-control form-control-lg form-control-solid" type="password"
                    name="password" v-model="data.password" autocomplete="off" placeholder="Masukkan Password" />
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
            <button tabindex="3" type="submit" ref="submitButton" class="btn btn-lg btn-primary w-100 mb-5">
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
            login: Yup.string()
                .required("Harap masukkan Username atau Email")
                .min(3, "Minimal 3 karakter")
                .max(100, "Maksimal 100 karakter"),
            password: Yup.string()
                .min(8, "Password minimal terdiri dari 8 karakter")
                .required("Harap masukkan password"),
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

            axios.post("/auth/login", {
                login: this.data.login,
                password: this.data.password
            })
                .then((res) => {
                    this.store.setAuth(res.data.user, res.data.token);

                    const roleName = res.data.user.role.name.toLowerCase();

                    if (roleName === "admin") {
                        this.router.push("/dashboard");
                    } else if (roleName === "bendahara") {
                        this.router.push("/bendahara/dashboard");
                    } else if (roleName === "siswa") {
                        this.router.push("/student/dashboard");
                    } else {
                        this.router.push("/student/dashboard");
                    }
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
