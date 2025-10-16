<template>
    <!--begin::Form-->
    <div class="w-100">
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <router-link to="/">
                <img :src="setting?.logo" :alt="setting?.app" class="w-200px mb-8" />
            </router-link>

            <h1 class="mb-3">
                Masuk ke <span class="text-primary">{{ setting?.app }}</span>
            </h1>
        </div>
        <!--end::Heading-->

        <!--begin::Tabs-->
        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#with-username-and-email">Username/Email</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#with-student">{{ $t('Siswa') }}</a>
            </li>
        </ul>
        <!--end::Tabs-->

        <!--begin::Tab Content-->
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="with-username-and-email" role="tabpanel">
                <WithUsernameAndEmail />
            </div>

            <div class="tab-pane fade" id="with-student" role="tabpanel">
                <WithStudent />
            </div>
        </div>
        <!--end::Tab Content-->

        <div class="border-bottom border-gray-300 w-100 mt-5 mb-10"></div>

        <div class="text-gray-400 fw-semobold fs-4 text-center">
            {{ $t('Belum punya akun?') }}
            <router-link to="/sign-up" class="link-primary fw-bold">
                {{ $t('Daftar') }}
            </router-link>
        </div>
    </div>
    <!--end::Form-->
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import { blockBtn, unblockBtn } from "@/libs/utils";
import { getAssetPath } from "@/core/helpers/assets";
import { useSetting } from "@/services";

// ✅ pastikan semua tab diimpor dan diregistrasi
import WithEmail from "./tabs/WithEmail.vue";
import WithUsernameAndEmail from "./tabs/WithUsernameAndEmail.vue";
import WithStudent from "./tabs/WithStudent.vue";

export default defineComponent({
    name: "sign-in",
    components: {
        WithEmail,
        WithUsernameAndEmail,
        WithStudent, // ✅ tambahkan ini
    },
    setup() {
        const store = useAuthStore();
        const router = useRouter();
        const { data: setting = {} } = useSetting();

        const submitButton = ref<HTMLElement | null>(null);

        const login = Yup.object().shape({
            identifier: Yup.string()
                .required("Harap masukkan Username / Email")
                .label("Identifier"),
            password: Yup.string()
                .min(8, "Password minimal terdiri dari 8 karakter")
                .required("Harap masukkan password")
                .label("Password"),
        });

        return {
            login,
            submitButton,
            getAssetPath,
            store,
            router,
            setting,
        };
    },
    data() {
        return {
            data: {
                identifier: null,
                password: null,
            },
            check: {
                type: "",
                error: "",
            },
        };
    },
    methods: {
        submit() {
            blockBtn(this.submitButton);

            axios
                .post("/auth/login", { ...this.data, type: this.check.type })
                .then((res) => {
                    this.store.setAuth(res.data.user, res.data.token);

                    const roleName = (res.data.user.role?.name || res.data.user.roles?.[0]?.name || "")
                        .toLowerCase()
                        .trim();

                    if (roleName === "admin") {
                        this.router.push("/admin/dashboard");
                    } else if (roleName === "bendahara") {
                        this.router.push("/bendahara/dashboard");
                    } else if (roleName === "siswa") {
                        this.router.push("/student/dashboard");
                    } else {
                        this.router.push("/cobadashboard");
                    }
                })
                .catch((error) => {
                    toast.error(error.response?.data?.message || "Terjadi kesalahan");
                })
                .finally(() => {
                    unblockBtn(this.submitButton);
                });
        },
        checkInput(value: string) {
            this.check.type = "";
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
                this.check.type = "email";
            } else {
                this.check.type = "username";
            }
        },
    },
});
</script>
