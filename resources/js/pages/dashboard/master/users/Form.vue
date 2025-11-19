<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed, nextTick } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { User, Student, Role, Teacher } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useStudent } from "@/services/useStudent";
import { useRole } from "@/services/useRole";
import { useTeacher } from "@/services/useTeacher";

const props = defineProps({
    selected: { type: String, default: null },
});

const emit = defineEmits(["close", "refresh"]);

const user = ref<User>({} as User);
const selectedRoleId = ref<string | null>(null);
const showRoleModal = ref(false);
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const photo = ref<any>([]);
const formRef = ref();

const formSchema = Yup.object().shape({
    username: Yup.string()
        .required("Username harus diisi")
        .max(50, "Maksimal 50 karakter")
        .matches(/^[a-zA-Z0-9_-]+$/, "Username hanya boleh huruf, angka, _ atau -"),
    name: Yup.string().required("Nama harus diisi"),
    email: Yup.string().email("Email harus valid").required("Email harus diisi"),
    password: Yup.string().min(8, "Minimal 8 karakter").nullable(),
    passwordConfirmation: Yup.string()
        .oneOf([Yup.ref("password")], "Konfirmasi password harus sama")
        .nullable(),
    role_id: Yup.string().required("Pilih role"),
    student_id: Yup.string().nullable(),
    teacher_id: Yup.string().nullable(),
});

function getEdit() {
    block(document.getElementById("form-user"));
    ApiService.get("master/users", props.selected)
        .then(({ data }) => {
            user.value = data.user;
            photo.value = data.user.photo ? ["/storage/" + data.user.photo] : [];
            selectedRoleId.value = data.user.role_id;
        })
        .catch((err: any) => {
            toast.error(err.response?.data?.message || "Gagal mengambil data");
        })
        .finally(() => unblock(document.getElementById("form-user")));
}

function submit() {
    const formData = new FormData();
    formData.append("username", user.value.username);
    formData.append("name", user.value.name);
    formData.append("email", user.value.email);
    formData.append("role_id", user.value.role_id);
    formData.append("status", "Aktif");

    if (user.value.student_id) formData.append("student_id", user.value.student_id);
    if (user.value.teacher_id) formData.append("teacher_id", user.value.teacher_id);

    if (user.value?.password) {
        formData.append("password", user.value.password);
        formData.append("password_confirmation", user.value.passwordConfirmation);
    }

    if (photo.value.length && photo.value[0].file)
        formData.append("photo", photo.value[0].file);

    if (props.selected) formData.append("_method", "PUT");

    block(document.getElementById("form-user"));
    axios({
        method: "post",
        url: props.selected
            ? `/master/users/${props.selected}`
            : "/master/users/store",
        data: formData,
        headers: { "Content-Type": "multipart/form-data" },
    })
        .then(() => {
            emit("close");
            emit("refresh");
            toast.success("Data berhasil disimpan");
            formRef.value.resetForm();
            selectedRoleId.value = null;
        })
        .catch((err: any) => {
            if (err.response?.status === 422) {
                const errors = err.response.data.errors;
                formRef.value.setErrors(errors);
                Object.values(errors).forEach((messages: any) =>
                    toast.error(messages[0])
                );
            } else {
                toast.error(err.response?.data?.message || "Terjadi kesalahan server");
            }
        })
        .finally(() => unblock(document.getElementById("form-user")));
}

// === Data ===
const student = useStudent();
const students = computed(() =>
    student.data.value?.map((item: Student) => ({
        id: item.id,
        text: item.nama,
        email: item.email,
    }))
);

const teacher = useTeacher();
const teachers = computed(() =>
    teacher.data.value?.map((item: Teacher) => ({
        id: item.id,
        text: item.nama,
        email: item.email,
    }))
);

const role = useRole();
const roles = computed(() =>
    role.data.value?.map((item: Role) => ({
        id: item.id,
        text: item.full_name,
    }))
);

watch(
    () => user.value.student_id,
    (newId) => {
        if (!newId) return;
        const s = students.value?.find((t) => t.id == newId);
        if (s) {
            user.value.name = s.text;
            user.value.email = s.email;
        }
    }
);

watch(
    () => user.value.teacher_id,
    (newId) => {
        if (!newId) return;
        const t = teachers.value?.find((x) => x.id == newId);
        if (t) {
            user.value.name = t.text;
            user.value.email = t.email;
        }
    }
);

onMounted(async () => {
    if (props.selected) {
        getEdit();
    } else {
        await nextTick();
        showRoleModal.value = true;
    }
});

function selectRole(id: string) {
    selectedRoleId.value = id;
    user.value.role_id = id;
    showRoleModal.value = false;
}
</script>

<template>
    <!-- ✅ Modal Pilih Role -->
    <div class="modal fade show" tabindex="-1" style="display: block; background: rgba(0,0,0,0.4)" v-if="showRoleModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Role Pengguna</h5>
                    <button type="button" class="btn-close" @click="emit('close')"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <button v-for="r in roles" :key="r.id" class="btn text-white"
                            style="background-color: #6f42c1; border-color: #6f42c1;" @click="selectRole(r.id)">
                            Tambah {{ r.text }}
                        </button>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-light-danger" @click="emit('close')">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Form utama -->
    <VForm v-if="!showRoleModal" class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-user"
        ref="formRef">
        <div class="card-header align-items-center">
            <h2 class="mb-0">
                {{ props.selected ? "Edit" : "Tambah" }} Pengguna
            </h2>
            <button type="button" class="btn btn-sm btn-light-danger ms-auto"
                @click="props.selected ? emit('close') : (showRoleModal = true)">
                {{ props.selected ? "Batal" : "Ganti Role" }}
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Pilih Siswa -->
                <div class="col-md-6" v-if="selectedRoleId == 3 || user.role_id == 3">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Pilih Siswa</label>
                        <Field name="student_id" type="hidden" v-model="user.student_id">
                            <select2 placeholder="Pilih siswa" class="form-select-solid" :options="students"
                                name="student_id" v-model="user.student_id" />
                        </Field>
                        <ErrorMessage name="student_id" class="text-danger small" />
                    </div>
                </div>

                <!-- Pilih Guru -->
                <div class="col-md-6" v-if="selectedRoleId != 3 && user.role_id != 3">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Pilih Guru</label>
                        <Field name="teacher_id" type="hidden" v-model="user.teacher_id">
                            <select2 placeholder="Pilih guru" class="form-select-solid" :options="teachers"
                                name="teacher_id" v-model="user.teacher_id" />
                        </Field>
                        <ErrorMessage name="teacher_id" class="text-danger small" />
                    </div>
                </div>

                <!-- Username -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Username</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="username"
                            autocomplete="off" v-model="user.username" placeholder="Masukkan Username" />
                        <ErrorMessage name="username" class="text-danger small" />
                    </div>
                </div>

                <!-- Nama -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Nama</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="name"
                            autocomplete="off" v-model="user.name" placeholder="Masukkan Nama"
                            :readonly="!!user.student_id || !!user.teacher_id" />
                        <ErrorMessage name="name" class="text-danger small" />
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Email</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="email"
                            v-model="user.email" :readonly="!!user.student_id || !!user.teacher_id"
                            placeholder="Masukkan Email" />
                        <ErrorMessage name="email" class="text-danger small" />
                    </div>
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Password</label>
                        <Field class="form-control form-control-lg form-control-solid" type="password" name="password"
                            autocomplete="off" v-model="user.password" placeholder="Masukkan password" />
                        <ErrorMessage name="password" class="text-danger small" />
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Konfirmasi Password
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="password"
                            name="passwordConfirmation" autocomplete="off" v-model="user.passwordConfirmation"
                            placeholder="Konfirmasi password" />
                        <ErrorMessage name="passwordConfirmation" class="text-danger small" />
                    </div>
                </div>

                <!-- Role -->
                <div class="col-md-6" v-if="props.selected">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Role</label>
                        <Field name="role_id" type="hidden" v-model="user.role_id">
                            <select2 placeholder="Pilih role" class="form-select-solid" :options="roles" name="role_id"
                                v-model="user.role_id" />
                        </Field>
                        <ErrorMessage name="role_id" class="text-danger small" />
                    </div>
                </div>

                <!-- Foto -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Foto</label>
                        <file-upload :files="photo" :accepted-file-types="fileTypes"
                            @updatefiles="(file) => (photo = file)" />
                        <ErrorMessage name="photo" class="text-danger small" />
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
