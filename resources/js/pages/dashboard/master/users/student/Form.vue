<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { User, Student } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useStudent } from "@/services/useStudent";

const STUDENT_ROLE_ID = 3;

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const user = ref<User>({} as User);
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const photo = ref<any>([]);
const formRef = ref();

const formSchema = Yup.object().shape({
    username: Yup.string()
        .required("Username harus diisi")
        .max(50, "Maksimal 50 karakter")
        .matches(/^[a-zA-Z0-9_-]+$/, "Username hanya boleh huruf, angka, _ atau -"),
    name: Yup.string().required("Nama harus diisi"),
    email: Yup.string()
        .email("Email harus valid")
        .required("Email harus diisi"),
    password: Yup.string().min(8, "Minimal 8 karakter").nullable(),
    passwordConfirmation: Yup.string()
        .oneOf([Yup.ref("password")], "Konfirmasi password harus sama")
        .nullable(),
    student_id: Yup.string().required("Pilih siswa"),
    status: Yup.string().required("Pilih status"),
});

function getEdit() {
    block(document.getElementById("form-user"));
    ApiService.get("master/users", props.selected)
        .then(({ data }) => {
            user.value = data.user;
            photo.value = data.user.photo
                ? ["/storage/" + data.user.photo]
                : [];
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-user"));
        });
}

function submit() {
    const formData = new FormData();
    formData.append("username", user.value.username);
    formData.append("name", user.value.name);
    formData.append("email", user.value.email);
    formData.append("role_id", STUDENT_ROLE_ID.toString());
    formData.append("status", user.value.status);

    if (user.value.student_id) {
        formData.append("student_id", user.value.student_id);
    }

    if (user.value?.password) {
        formData.append("password", user.value.password);
        formData.append(
            "password_confirmation",
            user.value.passwordConfirmation
        );
    }

    if (photo.value.length) {
        formData.append("photo", photo.value[0].file);
    }

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-user"));
    axios({
        method: "post",
        url: props.selected
            ? `/master/users/${props.selected}`
            : "/master/users/store",
        data: formData,
        headers: {
            "Content-Type": "multipart/form-data",
        },
    })
        .then(() => {
            emit("close");
            emit("refresh");
            toast.success("Data berhasil disimpan");
            formRef.value.resetForm();
        })
        .catch((err: any) => {
            if (err.response?.status === 422) {
                const errors = err.response.data.errors;
                formRef.value.setErrors(errors);
                Object.values(errors).forEach((messages: any) => {
                    toast.error(messages[0]);
                });
            } else {
                const message =
                    err.response?.data?.message || "Terjadi kesalahan server";
                toast.error(message);
            }
        })
        .finally(() => {
            unblock(document.getElementById("form-user"));
        });
}

// Ambil data siswa
const student = useStudent();
const students = computed(() =>
    student.data.value?.map((item: Student) => ({
        id: item.id,
        text: item.nama,
        email: item.email,
    }))
);

watch(
    () => user.value.student_id,
    (newId) => {
        if (!newId) return;
        const selectedStudent = students.value?.find((t) => t.id == newId);
        if (selectedStudent) {
            user.value.name = selectedStudent.text;
            user.value.email = selectedStudent.email;
        }
    }
);

onMounted(async () => {
    if (props.selected) {
        getEdit();
    }
});

watch(
    () => props.selected,
    () => {
        if (props.selected) {
            getEdit();
        }
    }
);
</script>

<template>
    <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-user" ref="formRef">
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Pengguna Siswa</h2>
            <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
                Batal
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Pilih Siswa
                        </label>
                        <Field name="student_id" type="hidden" v-model="user.student_id">
                            <select2 placeholder="Pilih siswa" class="form-select-solid" :options="students"
                                name="student_id" v-model="user.student_id" required />
                        </Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="student_id" class="text-danger small" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Username
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="username"
                            autocomplete="off" v-model="user.username" placeholder="Masukkan Username" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="username" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Nama
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="name"
                            autocomplete="off" v-model="user.name" placeholder="Masukkan Nama"
                            :readonly="!!user.student_id" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="name" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Email
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="email"
                            v-model="user.email" :readonly="!!user.student_id" placeholder="Masukkan Email" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="email" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Password
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="password" name="password"
                            autocomplete="off" v-model="user.password" placeholder="Masukkan password" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="password" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Konfirmasi Password
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="password"
                            name="passwordConfirmation" autocomplete="off" v-model="user.passwordConfirmation"
                            placeholder="Konfirmasi password" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="passwordConfirmation" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Status
                        </label>
                        <Field name="status" type="hidden" v-model="user.status">
                            <select2 placeholder="Pilih status" class="form-select-solid" :options="[
                                { id: 'Aktif', text: 'Aktif' },
                                { id: 'Tidak Aktif', text: 'Tidak Aktif' }
                            ]" name="status" v-model="user.status">
                            </select2>
                        </Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="status" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">
                            Foto
                        </label>
                        <file-upload :files="photo" :accepted-file-types="fileTypes"
                            v-on:updatefiles="(file) => (photo = file)"></file-upload>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="photo" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
                Simpan
            </button>
        </div>
    </VForm>
</template>
