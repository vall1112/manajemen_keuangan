<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref } from "vue";
import { toast } from "vue3-toastify";
import * as Yup from "yup";
import axios from "@/libs/axios";
import type { User } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useRouter } from "vue-router";

const emit = defineEmits(["refresh"]);
const router = useRouter();

const user = ref<User | any>({});
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const photo = ref<any>([]);
const formRef = ref();

const formSchema = Yup.object().shape({
    username: Yup.string().required("Username harus diisi"),
    name: Yup.string().required("Nama harus diisi"),
    email: Yup.string().email("Email harus valid").required("Email harus diisi"),
    password: Yup.string().nullable(),
    password_confirmation: Yup.string()
        .oneOf([Yup.ref("password")], "Konfirmasi password tidak sama")
        .nullable(),
});

function getProfile() {
    block(document.getElementById("form-user"));
    ApiService.get("/profile")
        .then(({ data }) => {
            user.value = data.user;
            photo.value = data.user.photo ? ["/storage/" + data.user.photo] : [];
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
    formData.append("role_id", user.value.role_id);
    formData.append("student_id", user.value.student_id);

    if (user.value?.password) {
        formData.append("password", user.value.password);
        formData.append(
            "password_confirmation",
            user.value.passwordConfirmation ?? ""
        );
    }

    if (photo.value?.[0]) {
        if (photo.value[0] instanceof File) {
            formData.append("photo", photo.value[0]);
        } else if (photo.value[0].file instanceof File) {
            formData.append("photo", photo.value[0].file);
        }
    }

    block(document.getElementById("form-user"));
    axios({
        method: "post",
        url: "/profile/update/user",
        data: formData,
        headers: {
            "Content-Type": "multipart/form-data",
        },
    })
        .then(() => {
            emit("refresh");
            toast.success("Profil berhasil diperbarui");
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
                const message = err.response?.data?.message || "Terjadi kesalahan server";
                toast.error(message);
            }
        })
        .finally(() => {
            unblock(document.getElementById("form-user"));
        });
}

onMounted(() => {
    getProfile();
});
</script>

<template>
    <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-user" ref="formRef">
        <!-- Header -->
        <div class="card-header align-items-center">
            <h2 class="mb-0">Kelola Profil Anda</h2>
            <div class="ms-auto d-flex gap-2">
                <button type="button" class="btn btn-sm btn-light-danger" @click="router.back()">
                    Kembali
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body">
            <div class="row">
                <!-- Kolom Kiri (input teks) -->
                <div class="col-md-6">
                    <!-- Username -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Username</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="username"
                            v-model="user.username" placeholder="Masukkan Username" />
                        <ErrorMessage name="username" class="text-danger small" />
                    </div>

                    <!-- Name -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Nama</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="name"
                            v-model="user.name" placeholder="Masukkan Nama" />
                        <ErrorMessage name="name" class="text-danger small" />
                    </div>

                    <!-- Email -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Email</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="email"
                            v-model="user.email" placeholder="Masukkan Email" />
                        <ErrorMessage name="email" class="text-danger small" />
                    </div>

                    <!-- Password -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Password</label>
                        <Field class="form-control form-control-lg form-control-solid" type="password" name="password"
                            v-model="user.password" placeholder="Kosongkan jika tidak diganti" />
                        <ErrorMessage name="password" class="text-danger small" />
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Konfirmasi Password</label>
                        <Field class="form-control form-control-lg form-control-solid" type="password"
                            name="password_confirmation" v-model="user.password_confirmation"
                            placeholder="Ulangi Password" />
                        <ErrorMessage name="password_confirmation" class="text-danger small" />
                    </div>
                </div>

                <!-- Kolom Kanan (photo) -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Foto</label>
                        <file-upload :files="photo" :accepted-file-types="fileTypes" required
                            v-on:updatefiles="(file) => (photo = file)"></file-upload>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
                Simpan
            </button>
        </div>
    </VForm>
</template>
