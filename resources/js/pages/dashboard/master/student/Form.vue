<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Classroom } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useClassroom } from "@/services/useClassroom";

const props = defineProps({ selected: { type: String, default: null } });
const emit = defineEmits(["close", "refresh"]);

// State Siswa
const student = ref<any>({});
const foto = ref<any>([]);
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const formRef = ref();

// State Pengguna
const user = ref<any>({
  id: null,
  username: "",
  name: "",
  email: "",
  password: "",
  passwordConfirmation: "",
  status: "Aktif",
  role_id: "3",
  photo: null,
});

// Auto sync student.nama → user.name
watch(() => student.value.nama, v => user.value.name = v);

watch(foto, () => {
  if (foto.value.length && foto.value[0].file) user.value.photo = foto.value[0].file;
});

// Data Kelas
const classroom = useClassroom();
const classrooms = computed(() =>
  classroom.data.value?.map((item: Classroom) => ({ id: item.id, text: item.nama_kelas }))
);

// Validasi
const formSchema = Yup.object().shape({
  nis: Yup.string().required("NIS harus diisi"),
  nama: Yup.string().required("Nama harus diisi"),
  email: Yup.string().email("Email harus valid").nullable(),
  telepon: Yup.string().nullable(),
  jenis_kelamin: Yup.string().required("Pilih jenis kelamin"),
  tempat_lahir: Yup.string().required("Tempat lahir harus diisi"),
  tanggal_lahir: Yup.date().required("Tanggal lahir harus diisi"),
  alamat: Yup.string().required("Alamat harus diisi"),
  classroom_id: Yup.string().required("Kelas harus dipilih"),
  status: Yup.string().required("Status harus dipilih"),
  username: Yup.string().required("Username harus diisi"),
  password: Yup.string().nullable(),
  passwordConfirmation: Yup.string().oneOf(
    [Yup.ref("password")],
    "Konfirmasi password harus sama dengan password"
  ),
});

// Edit Data
async function getEdit() {
  block(document.getElementById("form-student"));
  try {
    const { data } = await ApiService.get("master/students", props.selected);
    student.value = data.student;
    foto.value = data.student.foto ? ["/storage/" + data.student.foto] : [];

    const res = await axios.post(`/master/users/by-student/${props.selected}`);
    if (res.data.exists && res.data.user) {
      const u = res.data.user;
      user.value = {
        id: u.uuid,
        username: u.username,
        email: u.email,
        name: u.name,
        status: u.status,
        password: "",
        passwordConfirmation: "",
        role_id: u.role_id,
        photo: u.photo ? ["/storage/" + u.photo] : [],
      };
    } else {
      user.value = {
        id: null,
        username: "",
        email: student.value.email || "",
        name: student.value.nama,
        status: "Aktif",
        password: "",
        passwordConfirmation: "",
        role_id: "3",
        photo: [],
      };
    }
  } catch (e: any) {
    toast.error("Gagal mengambil data siswa & user");
  } finally {
    unblock(document.getElementById("form-student"));
  }
}

// Tombol Sumbit
async function submit() {
  block(document.getElementById("form-student"));
  try {
    // Step 1 — Save Data Murid
    const studentForm = new FormData();
    const studentFields = [
      "nis", "nama", "email", "telepon", "tempat_lahir", "tanggal_lahir",
      "jenis_kelamin", "alamat", "classroom_id", "status",
      "nama_ayah", "telepon_ayah", "nama_ibu", "telepon_ibu"
    ];
    studentFields.forEach(k => studentForm.append(k, student.value[k] ?? ""));
    if (foto.value.length && foto.value[0].file) studentForm.append("foto", foto.value[0].file);

    let studentId = props.selected;
    if (!props.selected) {
      const resStudent = await axios.post("/master/students/store", studentForm);
      studentId = resStudent.data.student.id;
      props.selected = studentId;
    } else {
      studentForm.append("_method", "PUT");
      await axios.post(`/master/students/${props.selected}`, studentForm);
    }

    // Step 2 — Save Data Pengguna
    const userForm = new FormData();
    userForm.append("username", user.value.username);
    userForm.append("name", user.value.name);
    userForm.append("email", user.value.email || student.value.email);
    userForm.append("status", user.value.status);
    userForm.append("role_id", "3");
    userForm.append("student_id", studentId);
    if (foto.value.length && foto.value[0].file) userForm.append("photo", foto.value[0].file);

    if (!user.value.id || user.value.password) {
      if (!user.value.password || user.value.password !== user.value.passwordConfirmation) {
        toast.error("Password dan konfirmasi password harus sama");
        unblock(document.getElementById("form-student"));
        return;
      }
      userForm.append("password", user.value.password);
      userForm.append("password_confirmation", user.value.passwordConfirmation);
    }

    if (!user.value.id) {
      await axios.post("/master/users/store", userForm);
    } else {
      userForm.append("_method", "PUT");
      await axios.post(`/master/users/${user.value.id}`, userForm);
    }

    toast.success("Berhasil menyimpan data siswa & user");
    emit("close");
    emit("refresh");
    formRef.value.resetForm();
  } catch (e: any) {
    toast.error(e.response?.data?.message || "Gagal menyimpan data");
    if (e.response?.data?.errors && formRef.value) {
      formRef.value.setErrors(e.response.data.errors);
    }
  } finally {
    unblock(document.getElementById("form-student"));
  }
}

onMounted(() => { if (props.selected) getEdit(); });
</script>

<template>
  <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-student" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Siswa</h2>
      <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
        Batal
        <i class="la la-times-circle p-0"></i>
      </button>
    </div>

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

        <!-- Password -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Password</label>
            <Field class="form-control form-control-lg form-control-solid" type="password" name="password"
              v-model="user.password" placeholder="Masukkan Password" />
            <ErrorMessage name="password" class="text-danger small" />
          </div>
        </div>

        <!-- Password Konfirmasi -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Konfirmasi Password</label>
            <Field class="form-control form-control-lg form-control-solid" type="password" name="passwordConfirmation"
              v-model="user.passwordConfirmation" placeholder="Konfirmasi Password" />
            <ErrorMessage name="passwordConfirmation" class="text-danger small" />
          </div>
        </div>

        <!-- Nama -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Nama</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="nama"
              v-model="student.nama" placeholder="Masukkan Nama" />
            <ErrorMessage name="nama" class="text-danger small" />
          </div>
        </div>

        <!-- NIS -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">NIS</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="nis" v-model="student.nis"
              placeholder="Masukkan NIS" />
            <ErrorMessage name="nis" class="text-danger small" />
          </div>
        </div>

        <!-- Jenis Kelamin -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Jenis Kelamin</label>
            <Field as="select" name="jenis_kelamin" v-model="student.jenis_kelamin"
              class="form-select form-select-lg form-select-solid">
              <option value="">Pilih Jenis Kelamin</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </Field>
            <ErrorMessage name="jenis_kelamin" class="text-danger small" />
          </div>
        </div>

        <!-- Tempat Lahir -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tempat Lahir</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="tempat_lahir"
              v-model="student.tempat_lahir" placeholder="Masukkan Tempat Lahir" />
            <ErrorMessage name="tempat_lahir" class="text-danger small" />
          </div>
        </div>

        <!-- Tanggal Lahir -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tanggal Lahir</label>
            <Field class="form-control form-control-lg form-control-solid" type="date" name="tanggal_lahir"
              v-model="student.tanggal_lahir" />
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
            <label class="form-label fw-bold fs-6">Telepon</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="telepon"
              v-model="student.telepon" placeholder="Masukkan Telepon" />
            <ErrorMessage name="telepon" class="text-danger small" />
          </div>
        </div>

        <!-- Alamat -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Alamat</label>
            <Field as="textarea" class="form-control form-control-lg form-control-solid" name="alamat"
              v-model="student.alamat" placeholder="Masukkan Alamat" />
            <ErrorMessage name="alamat" class="text-danger small" />
          </div>
        </div>

        <!-- Kelas -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Kelas</label>
            <Field name="classroom_id" type="hidden" v-model="student.classroom_id">
              <select2 placeholder="Pilih Kelas" class="form-select-solid" :options="classrooms"
                v-model="student.classroom_id"></select2>
            </Field>
            <ErrorMessage name="classroom_id" class="text-danger small" />
          </div>
        </div>

        <!-- Status -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Status</label>
            <Field as="select" name="status" v-model="student.status"
              class="form-select form-select-lg form-select-solid">
              <option value="">Pilih Status</option>
              <option value="Aktif">Aktif</option>
              <option value="Prakerin">Prakerin</option>
              <option value="Alumni">Alumni</option>
              <option value="Keluar">Keluar</option>
            </Field>
            <ErrorMessage name="status" class="text-danger small" />
          </div>
        </div>

        <!-- Foto -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Foto</label>
            <file-upload :files="foto" :accepted-file-types="fileTypes"
              v-on:updatefiles="(file) => (foto = file)"></file-upload>
            <ErrorMessage name="foto" class="text-danger small" />
          </div>
        </div>

        <!-- Nama Ayah -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Nama Ayah</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="nama_ayah"
              v-model="student.nama_ayah" placeholder="Masukkan Nama Ayah" />
            <ErrorMessage name="nama_ayah" class="text-danger small" />
          </div>
        </div>

        <!-- Telepon Ayah -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Telepon Ayah</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="telepon_ayah"
              v-model="student.telepon_ayah" placeholder="Masukkan Telepon Ayah" />
            <ErrorMessage name="telepon_ayah" class="text-danger small" />
          </div>
        </div>

        <!-- Nama Ibu -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Nama Ibu</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="nama_ibu"
              v-model="student.nama_ibu" placeholder="Masukkan Nama Ibu" />
            <ErrorMessage name="nama_ibu" class="text-danger small" />
          </div>
        </div>

        <!-- Telepon Ibu -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Telepon Ibu</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="telepon_ibu"
              v-model="student.telepon_ibu" placeholder="Masukkan Telepon Ibu" />
            <ErrorMessage name="telepon_ibu" class="text-danger small" />
          </div>
        </div>

      </div>
    </div>

    <div class="card-footer d-flex">
      <button type="submit" class="btn btn-primary btn-sm ms-auto">Simpan</button>
    </div>
  </VForm>
</template>
