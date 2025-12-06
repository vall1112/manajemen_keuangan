<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref } from "vue";
import { toast } from "vue3-toastify";
import * as Yup from "yup";
import axios from "@/libs/axios";
import type { Student, User } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useRouter } from "vue-router";

const emit = defineEmits(["refresh"]);
const router = useRouter();

const user = ref<User | any>({});
const student = ref<Student>({} as Student);
const foto = ref<any>(null);
const formRef = ref();

const formSchema = Yup.object().shape({
    username: Yup.string().required("Username harus diisi"),
    password: Yup.string().nullable(),
    password_confirmation: Yup.string()
        .oneOf([Yup.ref("password")], "Konfirmasi password tidak sama")
        .nullable(),
    email: Yup.string().email("Email harus valid").required("Email harus diisi"),
    telepon: Yup.string().required("Nomor telepon harus diisi"),
    alamat: Yup.string().required("Alamat harus diisi"),
});

function formatTanggalIndo(dateString: string) {
    const tgl = new Date(dateString);
    return new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    }).format(tgl);
}

function getProfile() {
    block(document.getElementById("form-student"));
    ApiService.get("/profile")
        .then(({ data }) => {
            student.value = data.siswa;
            user.value = data.user;

            student.value.tanggal_lahir = student.value.tanggal_lahir;

            if (student.value.tanggal_lahir) {
                student.value.tanggal_lahir = formatTanggalIndo(
                    student.value.tanggal_lahir
                );
            }

            foto.value = data.siswa.foto ? ["/storage/" + data.siswa.foto] : [];
        })
        .finally(() => {
            unblock(document.getElementById("form-student"));
        });
}

function submit() {
    const formData = new FormData();

    formData.append("username", user.value.username);
    if (user.value.password) {
        formData.append("password", user.value.password);
        formData.append(
            "password_confirmation",
            user.value.password_confirmation ?? ""
        );
    }

    formData.append("nama", student.value.nama);
    formData.append("nis", student.value.nis);
    formData.append("jenis_kelamin", student.value.jenis_kelamin);
    formData.append("tempat_lahir", student.value.tempat_lahir);

    if (student.value.tanggal_lahir) {
        const d = new Date(student.value.tanggal_lahir);
        const onlyDate = d.toISOString().split("T")[0];
        formData.append("tanggal_lahir", onlyDate);
    }

    formData.append("email", student.value.email ?? "");
    formData.append("telepon", student.value.telepon);
    formData.append("alamat", student.value.alamat);
    formData.append("classroom_id", student.value.classroom_id);
    formData.append("status", student.value.status);

    ApiService.post("/profile/update/student", formData)
        .then(() => toast.success("Profil berhasil diperbarui"))
        .catch(() => toast.error("Gagal memperbarui profil"));
}

onMounted(() => {
    getProfile();
});
</script>

<template>
    <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-student" ref="formRef">
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
                <!-- Username -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Username</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="username"
                            v-model="user.username" placeholder="Masukkan Username" />
                        <ErrorMessage name="username" class="text-danger small" />
                    </div>
                </div>

                <!-- Nama -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Nama</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="nama"
                            v-model="student.nama" disabled />
                    </div>
                </div>

                <!-- Kelas -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Kelas</label>
                        <input type="text" class="form-control form-control-lg form-control-solid"
                            :value="student.classroom?.nama_kelas ?? '-'" disabled />
                    </div>
                </div>

                <!-- NIS -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">NIS</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="nis"
                            v-model="student.nis" disabled />
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Jenis Kelamin</label>
                        <input type="text" class="form-control form-control-lg form-control-solid" :value="student.jenis_kelamin === 'L'
                            ? 'Laki-laki'
                            : student.jenis_kelamin === 'P'
                                ? 'Perempuan'
                                : '-'
                            " disabled />
                    </div>
                </div>

                <!-- Tempat Lahir -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Tempat Lahir</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="tempat_lahir"
                            v-model="student.tempat_lahir" disabled />
                    </div>
                </div>

                <!-- Tanggal Lahir -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Tanggal Lahir</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="tanggal_lahir"
                            v-model="student.tanggal_lahir" disabled />
                        <ErrorMessage name="tanggal_lahir" class="text-danger small" />
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Email</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="email"
                            v-model="student.email" placeholder="Masukkan Email" />
                        <ErrorMessage name="email" class="text-danger small" />
                    </div>
                </div>

                <!-- Telepon -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Telepon</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="telepon"
                            v-model="student.telepon" placeholder="Masukkan Telepon" />
                        <ErrorMessage name="telepon" class="text-danger small" />
                    </div>
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Password</label>
                        <Field class="form-control form-control-lg form-control-solid" type="password" name="password"
                            v-model="user.password" placeholder="Kosongkan jika tidak diganti" />
                        <ErrorMessage name="password" class="text-danger small" />
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Konfirmasi Password</label>
                        <Field class="form-control form-control-lg form-control-solid" type="password"
                            name="password_confirmation" v-model="user.password_confirmation"
                            placeholder="Ulangi Password" />
                        <ErrorMessage name="password_confirmation" class="text-danger small" />
                    </div>
                </div>

                <!-- Alamat -->
                <div class="col-md-12">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Alamat</label>
                        <Field as="textarea" class="form-control form-control-lg form-control-solid" name="alamat"
                            v-model="student.alamat" placeholder="Masukkan Alamat" />
                        <ErrorMessage name="alamat" class="text-danger small" />
                    </div>
                </div>

                <!-- Foto -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Foto</label>
                        <file-upload :files="foto" :disabled="true" />
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
