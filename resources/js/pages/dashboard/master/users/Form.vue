<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { User, Role } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useRole } from "@/services/useRole";
import { useStudent } from "@/services/useStudent";
import { useTeacher } from "@/services/useTeacher";

// Props
const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

// Emit
const emit = defineEmits(["close", "refresh"]);

// Data user
const user = ref<User>({
    status: "Aktif",
} as User);

// File upload
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const photo = ref<any>([]);
const formRef = ref();

// Yup Validation Schema
const formSchema = Yup.object().shape({
    name: Yup.string().required("Nama harus diisi"),
    email: Yup.string().email("Email harus valid").required("Email harus diisi"),
    username: Yup.string().required("Username wajib diisi"),
    password: Yup.string().min(8, "Minimal 8 karakter").nullable(),
    passwordConfirmation: Yup.string()
        .oneOf([Yup.ref("password")], "Konfirmasi password harus sama")
        .nullable(),
    role_id: Yup.string().nullable(),
    teacher_id: Yup.string().nullable(),
    student_id: Yup.string().nullable(),
});

// ==========================================
// LOAD EDIT DATA USER
// ==========================================
function getEdit() {
    block(document.getElementById("form-user"));
    ApiService.get("master/users", props.selected)
        .then(({ data }) => {
            user.value = data.user;
            user.value.status = "Aktif";

            photo.value = data.user.photo ? ["/storage/" + data.user.photo] : [];
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-user"));
        });
}

// ==========================================
// SUBMIT FORM
// ==========================================
function submit() {
    console.log("ROLE TERPILIH:", user.value.role_id);

    const formData = new FormData();
    formData.append("name", user.value.name);
    formData.append("email", user.value.email);
    formData.append("username", user.value.username);
    formData.append("role_id", user.value.role_id);
    formData.append("status", "Aktif");

    if (user.value.teacher_id) formData.append("teacher_id", user.value.teacher_id);
    if (user.value.student_id) formData.append("student_id", user.value.student_id);

    if (user.value?.password) {
        formData.append("password", user.value.password);
        formData.append("password_confirmation", user.value.passwordConfirmation);
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
            const errors = err.response?.data?.errors;

            if (errors) formRef.value.setErrors(errors);

            if (errors) {
                Object.values(errors).forEach((msg: any) => {
                    toast.error(msg[0]);
                });
            } else {
                toast.error(err.response?.data?.message || "Terjadi kesalahan.");
            }
        })
        .finally(() => {
            unblock(document.getElementById("form-user"));
        });
}

// ==========================================
// LOAD ROLE, STUDENTS, TEACHER
// ==========================================
const role = useRole();
const roles = computed(() =>
    role.data.value?.map((item: Role) => ({
        id: item.id,
        text: item.full_name,
    }))
);

const student = useStudent();
const students = computed(() =>
    student.data.value?.map((item: any) => ({
        id: item.id,
        text: `${item.nama} — ${item.classroom?.nama_kelas ?? "Tidak ada kelas"}`,
    }))
);

const teacher = useTeacher();
const teachers = computed(() =>
    teacher.data.value?.map((item: any) => ({
        id: item.id,
        text: item.nama,
    }))
);

// Load when mounted
onMounted(() => {
    if (props.selected) getEdit();
});

// Reload when modal open
watch(
    () => props.selected,
    () => {
        if (props.selected) getEdit();
    }
);

// ==========================================
// RESET GURU/SISWA SAAT ROLE BERUBAH
// ==========================================
watch(
    () => user.value.role_id,
    (val) => {
        if (val == 1 || val == 2 || val == 4) {
            user.value.student_id = null;
        }

        if (val == 3) {
            user.value.teacher_id = null;
        }
    }
);

// ==========================================
// AUTO FILL NAMA/EMAIL GURU
// ==========================================
watch(
    () => user.value.teacher_id,
    (val) => {
        if (val && (user.value.role_id == 1 || user.value.role_id == 2 || user.value.role_id == 4)) {
            const selected = teacher.data.value?.find((t: any) => t.id == val);
            if (selected) {
                user.value.name = selected.nama;
                user.value.email = selected.email;
            }
        }
    }
);

// ==========================================
// AUTO FILL NAMA/EMAIL SISWA
// ==========================================
watch(
    () => user.value.student_id,
    (val) => {
        if (val && user.value.role_id == 3) {
            const selected = student.data.value?.find((s: any) => s.id == val);
            if (selected) {
                user.value.name = selected.nama;
                user.value.email = selected.email;
            }
        }
    }
);
</script>

<template>
    <VForm
        class="form card mb-10"
        @submit="submit"
        :validation-schema="formSchema"
        id="form-user"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Pengguna</h2>
            <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
                Batal <i class="la la-times-circle p-0"></i>
            </button>
        </div>

        <div class="card-body">
            <div class="row">

                <!-- ====================== -->
                <!-- ROLE (PERBAIKAN PENUH) -->
                <!-- ====================== -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Role</label>

                        <!-- FIX: TANPA Field AGAR VALUE TIDAK NYASAR -->
                        <select2
                            placeholder="Pilih role"
                            class="form-select-solid"
                            :options="roles"
                            name="role_id"
                            v-model="user.role_id"
                        />

                        <ErrorMessage name="role_id" class="text-danger" />
                    </div>
                </div>

                <!-- GURU -->
                <div class="col-md-6" v-if="user.role_id == 1 || user.role_id == 2 || user.role_id == 4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Guru</label>

                        <select2
                            placeholder="Pilih Guru"
                            class="form-select-solid"
                            :options="teachers"
                            name="teacher_id"
                            v-model="user.teacher_id"
                            allow-clear
                        />

                        <ErrorMessage name="teacher_id" class="text-danger" />
                    </div>
                </div>

                <!-- SISWA -->
                <div class="col-md-6" v-if="user.role_id == 3">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Siswa</label>

                        <select2
                            placeholder="Pilih Siswa"
                            class="form-select-solid"
                            :options="students"
                            name="student_id"
                            v-model="user.student_id"
                            allow-clear
                        />

                        <ErrorMessage name="student_id" class="text-danger" />
                    </div>
                </div>

                <!-- NAME -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Nama</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="name"
                            v-model="user.name" placeholder="Masukkan Nama"
                            :readonly="user.teacher_id || user.student_id" />
                        <ErrorMessage name="name" class="text-danger" />
                    </div>
                </div>

                <!-- EMAIL -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Email</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="email"
                            v-model="user.email" placeholder="Masukkan Email"
                            :readonly="user.teacher_id || user.student_id" />
                        <ErrorMessage name="email" class="text-danger" />
                    </div>
                </div>

                <!-- USERNAME -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Username</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="username"
                            v-model="user.username" placeholder="Masukkan Username" />
                        <ErrorMessage name="username" class="text-danger" />
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Password</label>
                        <Field class="form-control form-control-lg form-control-solid" type="password" name="password"
                            v-model="user.password" placeholder="Masukkan password" />
                        <ErrorMessage name="password" class="text-danger" />
                    </div>
                </div>

                <!-- PASSWORD CONFIRMATION -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Konfirmasi Password</label>
                        <Field class="form-control form-control-lg form-control-solid" type="password"
                            name="passwordConfirmation" v-model="user.passwordConfirmation"
                            placeholder="Konfirmasi password" />
                        <ErrorMessage name="passwordConfirmation" class="text-danger" />
                    </div>
                </div>

                <!-- FOTO -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Foto</label>
                        <file-upload :files="photo" :accepted-file-types="fileTypes"
                            v-on:updatefiles="(file) => (photo = file)"></file-upload>
                        <ErrorMessage name="photo" class="text-danger" />
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-light-primary btn-sm ms-auto">
                Simpan
            </button>
        </div>
    </VForm>
</template>
