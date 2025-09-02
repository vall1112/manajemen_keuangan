<template>
    <div class="w-lg-500px w-100">
        <main class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework">
            <!-- Heading -->
            <div class="mb-10 text-center">
                <router-link to="/">
                    <img :src="setting?.logo" :alt="setting?.app" class="w-200px mb-8" />
                </router-link>
                <h1 class="text-dark mb-3">
                    Daftar Akun ke <span class="text-primary">{{ setting?.app }}</span>
                </h1>
            </div>

            <!-- Stepper -->
            <div class="stepper stepper-links d-flex flex-column" id="kt_create_account_stepper"
                ref="horizontalWizardRef">
                <div class="stepper-nav py-5 mt-5 d-none">
                    <div class="stepper-item current" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Akun</h3>
                    </div>
                    <div class="stepper-item" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Verifikasi Email</h3>
                    </div>
                    <div class="stepper-item" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Password</h3>
                    </div>
                </div>

                <!-- Form -->
                <form class="mx-auto mw-600px w-100 pt-15 pb-10" novalidate id="kt_create_account_form"
                    @submit="handleStep">
                    <!-- Step 1: Credential -->
                    <div class="current" data-kt-stepper-element="content">
                        <Credential :formData="formData" />
                    </div>

                    <!-- Step 2: Email OTP -->
                    <div data-kt-stepper-element="content">
                        <VerifyEmail :formData="formData" @on-complete="handleOtpEmail" @send-otp="sendOtpEmail" />
                    </div>

                    <!-- Step 3: Password -->
                    <div data-kt-stepper-element="content">
                        <Password :formData="formData" />
                    </div>

                    <!-- Actions -->
                    <div class="d-flex flex-stack pt-15">
                        <div class="mr-2">
                            <button type="button" class="btn btn-lg btn-light-primary me-3" @click="previousStep">
                                <KTIcon icon-name="arrow-left" icon-class="fs-4 me-1" /> Kembali
                            </button>
                        </div>
                        <div>
                            <button type="submit" id="submit-form" class="btn btn-lg btn-primary me-3"
                                v-if="currentStepIndex === totalSteps - 1">
                                <span class="indicator-label">
                                    Daftar
                                    <KTIcon icon-name="arrow-right" icon-class="fs-3 ms-2 me-0" />
                                </span>
                                <span class="indicator-progress">
                                    Memproses... <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <button v-else type="submit" id="next-form" class="btn btn-lg btn-primary">
                                <span class="indicator-label">
                                    Selanjutnya
                                    <KTIcon icon-name="arrow-right" icon-class="fs-4 ms-2 me-0" />
                                </span>
                                <span class="indicator-progress">
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="border-bottom border-gray-300 w-100 mt-5 mb-10"></div>

            <!-- Link Sign-in -->
            <div class="text-gray-400 fw-semobold fs-4 text-center">
                Sudah memiliki akun?
                <router-link to="/auth/sign-in" class="link-primary fw-bold">Masuk</router-link>
            </div>
        </main>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from "vue";
import { useForm } from "vee-validate";
import * as Yup from "yup";
import create from "vue-zustand";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import { blockBtn, unblockBtn } from "@/libs/utils";
import router from "@/router";
import { StepperComponent } from "@/assets/ts/components";
import { useSetting } from "@/services";

import Credential from "./steps/Credential.vue";
import VerifyEmail from "./steps/VerifyEmail.vue";
import Password from "./steps/Password.vue";

interface ICredential {
    username?: string;
    nama?: string;
    email?: string;
    phone?: string;
}
interface IVerifyEmail { otp_email?: string; }
interface IPassword { password?: string; password_confirmation?: string; }

interface CreateAccount extends ICredential, IVerifyEmail, IPassword { }

interface IOtpInterval {
    otpInterval: number;
    setOtpInterval: (otpInterval: number) => void;
}

export const useOtpIntervalStore = create<IOtpInterval>((set) => ({
    otpInterval: 0,
    setOtpInterval: (otpInterval: number) => set({ otpInterval }),
}));

export default defineComponent({
    name: "sign-up",
    components: { Credential, VerifyEmail, Password },
    setup() {
        const { data: setting = {} } = useSetting();
        const _stepperObj = ref<StepperComponent | null>(null);
        const horizontalWizardRef = ref<HTMLElement | null>(null);
        const currentStepIndex = ref(0);

        const formData = ref<CreateAccount>({
            username: "",
            nama: "",
            email: "",
            phone: "",
            otp_email: "",
            password: "",
            password_confirmation: "",
        });

        onMounted(() => {
            _stepperObj.value = StepperComponent.createInsance(horizontalWizardRef.value as HTMLElement);
        });

        const createAccountSchema = [
            Yup.object({
                username: Yup.string()
                    .required("Username tidak boleh kosong")
                    .min(3, "Username minimal 3 karakter")
                    .max(50, "Username maksimal 50 karakter")
                    .matches(/^[a-zA-Z0-9_]+$/, "Username hanya boleh terdiri dari huruf, angka, dan underscore")
                    .label("Username"),
                nama: Yup.string().required("Nama tidak boleh kosong").label("Nama"),
                email: Yup.string().email().required("Email tidak boleh kosong").label("Email"),
                phone: Yup.string()
                    .matches(/^08[0-9]\d{8,11}$/, "No. Telepon tidak valid")
                    .required("No. Telepon tidak boleh kosong")
                    .label("No. Telepon"),
            }),
            Yup.object({}), // step verifikasi email
            Yup.object({
                password: Yup.string()
                    .min(8, "Password minimal terdiri dari 8 karakter")
                    .required("Password tidak boleh kosong")
                    .label("Password"),
                password_confirmation: Yup.string()
                    .oneOf([Yup.ref("password")], "Konfirmasi Password tidak sesuai")
                    .required("Konfirmasi Password tidak boleh kosong")
                    .label("Konfirmasi Password"),
            }),
        ];

        const currentSchema = computed(() => createAccountSchema[currentStepIndex.value]);
        const { resetForm, handleSubmit } = useForm<ICredential | IVerifyEmail | IPassword>({ validationSchema: currentSchema });
        const totalSteps = computed(() => _stepperObj.value ? _stepperObj.value.totalStepsNumber : 1);
        const { otpInterval, setOtpInterval } = useOtpIntervalStore();
        const timeIntv = ref<any>(null);

        const handleOtpInterval = () => {
            clearInterval(timeIntv.value);
            timeIntv.value = setInterval(() => {
                setOtpInterval.value(otpInterval.value - 1);
                if (otpInterval.value === 0) clearInterval(timeIntv.value);
            }, 1000);
        };

        const sendOtpEmail = (callback: any) => {
            blockBtn("#next-form");
            axios.post("/auth/register/get/email/otp", { email: formData.value.email, nama: formData.value.nama })
                .then(() => {
                    toast.success("Kode OTP berhasil dikirim ke Email Anda");
                    unblockBtn("#next-form");
                    callback && callback();
                    setOtpInterval.value(30);
                    handleOtpInterval();
                })
                .catch(err => { toast.error(err.response.data.message); unblockBtn("#next-form"); });
        };

        const checkOtpEmail = (callback: any) => {
            blockBtn("#next-form");
            axios.post("/auth/register/check/email/otp", { email: formData.value.email, otp: formData.value.otp_email })
                .then(() => { toast.success("Email berhasil diverifikasi"); unblockBtn("#next-form"); callback && callback(); })
                .catch(err => { toast.error(err.response.data.message); unblockBtn("#next-form"); });
        };

        const handleStep = handleSubmit((values) => {
            resetForm({ values: { ...formData.value } });
            if (currentStepIndex.value === 0) {
                sendOtpEmail(() => { formData.value = { ...values }; currentStepIndex.value++; _stepperObj.value?.goNext(); });
            } else if (currentStepIndex.value === 1) {
                checkOtpEmail(() => { formData.value = { ...values }; currentStepIndex.value++; _stepperObj.value?.goNext(); });
            } else if (currentStepIndex.value === 2) {
                formData.value = { ...values }; formSubmit(values);
            }
        });

        const previousStep = () => { currentStepIndex.value--; _stepperObj.value?.goPrev(); };

        const formSubmit = (values: CreateAccount) => {
            blockBtn("#submit-form");
            axios.post("/auth/register", values)
                .then(() => { toast.success("Akun berhasil dibuat"); router.push({ name: "sign-in" }); })
                .catch(err => { toast.error(err.response.data.message); unblockBtn("#submit-form"); });
        };

        return {
            horizontalWizardRef, previousStep, handleStep, formSubmit, totalSteps, currentStepIndex,
            formData, sendOtpEmail, resetForm, setting,
        };
    },
    methods: {
        handleOtpEmail(value: string) {
            this.resetForm({ values: { ...this.formData, otp_email: value } });
            this.formData.otp_email = value;
        },
    },
});
</script>
