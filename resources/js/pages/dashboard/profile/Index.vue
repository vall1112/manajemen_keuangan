<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref } from "vue";
import { toast } from "vue3-toastify";
import * as Yup from "yup";
import axios from "@/libs/axios";
import type { Student, User } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useRouter } from "vue-router";

// === Emits dan Router ===
const emit = defineEmits(["refresh"]);
const router = useRouter();

// === Variabel Reaktif ===
const user = ref<any>({});
const student = ref<Student>({} as Student);
const foto = ref<any>(null);
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const formRef = ref();

// === Skema Validasi Form ===
const formSchema = Yup.object().shape({
    username: Yup.string().required("Username harus diisi"),
    name: Yup.string().required("Nama harus diisi"),
    password: Yup.string().nullable(),
    password_confirmation: Yup.string()
        .oneOf([Yup.ref("password")], "Konfirmasi password tidak sama")
        .nullable(),
    email: Yup.string().email("Email harus valid").nullable(),
    telepon: Yup.string().required("Nomor telepon harus diisi"),
    alamat: Yup.string().required("Alamat harus diisi"),
    status: Yup.string().required("Status harus dipilih"),
    nama_ayah: Yup.string().nullable(),
    telepon_ayah: Yup.string().nullable(),
    nama_ibu: Yup.string().nullable(),
    telepon_ibu: Yup.string().nullable(),
    foto: Yup.mixed().nullable(),
});


function formatTanggalIndo(dateString: string) {
    const tgl = new Date(dateString);
    return new Intl.DateTimeFormat("id-ID", { day: "numeric", month: "long", year: "numeric" }).format(tgl);
}

// === Ambil data profil student ===
function getProfile() {
    block(document.getElementById("form-student"));
    ApiService.get("/profile")
        .then(({ data }) => {
            student.value = data.siswa;
            user.value = data.user; // simpan user juga

            // format tanggal lahir
            if (student.value.tanggal_lahir) {
                student.value.tanggal_lahir = formatTanggalIndo(student.value.tanggal_lahir);
            }

            foto.value = data.siswa.foto ? ["/storage/" + data.siswa.foto] : [];
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-student"));
        });
}

function parseTanggalIndo(tanggal: string) {
    const bulan = {
        Januari: "01",
        Februari: "02",
        Maret: "03",
        April: "04",
        Mei: "05",
        Juni: "06",
        Juli: "07",
        Agustus: "08",
        September: "09",
        Oktober: "10",
        November: "11",
        Desember: "12",
    };

    const [hari, namaBulan, tahun] = tanggal.split(" ");
    return `${tahun}-${bulan[namaBulan as keyof typeof bulan]}-${hari.padStart(2, "0")}`;
}

// === Submit Update Profil ===
function submit() {
    const formData = new FormData();

    // user
    formData.append("username", user.value.username);
    formData.append("name", user.value.name);
    if (user.value.password) {
        formData.append("password", user.value.password);
        formData.append("password_confirmation", user.value.password_confirmation ?? "");
    }

    // student
    formData.append("nama", student.value.nama);
    formData.append("nis", student.value.nis);
    formData.append("jenis_kelamin", student.value.jenis_kelamin);
    formData.append("tempat_lahir", student.value.tempat_lahir);
    formData.append("tanggal_lahir", student.value.tanggal_lahir);
    formData.append("email", student.value.email ?? "");
    formData.append("telepon", student.value.telepon);
    formData.append("alamat", student.value.alamat);
    formData.append("classroom_id", student.value.classroom_id);
    formData.append("status", student.value.status);
    formData.append("nama_ayah", student.value.nama_ayah ?? "");
    formData.append("telepon_ayah", student.value.telepon_ayah ?? "");
    formData.append("nama_ibu", student.value.nama_ibu ?? "");
    formData.append("telepon_ibu", student.value.telepon_ibu ?? "");

    block(document.getElementById("form-student"));
    axios({
        method: "post",
        url: "/profile/update",
        data: formData,
        headers: { "Content-Type": "multipart/form-data" },
    })
        .then(() => {
            toast.success("Profil berhasil diperbarui");
            setTimeout(() => {
                router.push({ name: "dashboard.profile" }).then(() => {
                    window.location.reload();
                });
            }, 2000);
        })
        .catch((err: any) => {
            formRef.value.setErrors(err.response.data.errors);
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-student"));
        });
}

// === onMounted ===
onMounted(() => {
    getProfile();
});
</script>

<template>
    <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-student" ref="formRef">
        <!-- Header -->
        <div class="card-header align-items-center">
            <h2 class="mb-0">Kelola Profil Anda</h2>
            <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
                Batal
                <i class="la la-times-circle p-0"></i>
            </button>
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
                        <ErrorMessage name="nama" class="text-danger small" />
                    </div>
                </div>

                <!-- Kelas -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Kelas</label>
                        <input type="text" class="form-control form-control-lg form-control-solid"
                            :value="student.classroom?.nama_kelas ?? '-'" disabled />
                        <ErrorMessage name="classroom_id" class="text-danger" />
                    </div>
                </div>

                <!-- NIS -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">NIS</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="nis"
                            v-model="student.nis" disabled />
                        <ErrorMessage name="nis" class="text-danger small" />
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Jenis Kelamin</label>
                        <input type="text" class="form-control form-control-lg form-control-solid"
                            :value="student.jenis_kelamin === 'L' ? 'Laki-laki' : student.jenis_kelamin === 'P' ? 'Perempuan' : '-'"
                            disabled />
                        <ErrorMessage name="jenis_kelamin" class="text-danger small" />
                    </div>
                </div>

                <!-- Tempat Lahir -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Tempat Lahir</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="tempat_lahir"
                            v-model="student.tempat_lahir" disabled />
                        <ErrorMessage name="tempat_lahir" class="text-danger small" />
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
                        <label class="form-label fw-bold fs-6">Email</label>
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

                <!-- Orang Tua -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Nama Ayah</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="nama_ayah"
                            v-model="student.nama_ayah" placeholder="Masukkan Nama Ayah" />
                        <ErrorMessage name="nama_ayah" class="text-danger small" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Telepon Ayah</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="telepon_ayah"
                            v-model="student.telepon_ayah" placeholder="Masukkan Telepon Ayah" />
                        <ErrorMessage name="telepon_ayah" class="text-danger small" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Nama Ibu</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="nama_ibu"
                            v-model="student.nama_ibu" placeholder="Masukkan Nama Ibu" />
                        <ErrorMessage name="nama_ibu" class="text-danger small" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Telepon Ibu</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="telepon_ibu"
                            v-model="student.telepon_ibu" placeholder="Masukkan Telepon Ibu" />
                        <ErrorMessage name="telepon_ibu" class="text-danger small" />
                    </div>
                </div>

                <!-- Foto -->
                <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Foto</label>
                        <file-upload :files="foto" :accepted-file-types="fileTypes" :disabled="true" />
                        <ErrorMessage name="foto" class="text-danger small" />
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