<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch } from "vue";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import ApiService from "@/core/services/ApiService";

const props = defineProps({
  selected: { type: String, default: null },
});
const emit = defineEmits(["close", "refresh"]);

// =============================
// STATE
// =============================
const teacher = ref<any>({});
const user = ref<any>({
  id: null,
  username: "",
  name: "",
  email: "",
  password: "",
  passwordConfirmation: "",
  status: "Aktif",
  role_id: "4",
  photo: null,
});

const foto = ref<any>([]);
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const formRef = ref();

// =============================
// AUTO SYNC USER.NAME = TEACHER.NAMA
// =============================
watch(() => teacher.value.nama, (v) => user.value.name = v);

watch(foto, () => {
  if (foto.value.length && foto.value[0].file) {
    user.value.photo = foto.value[0].file;
  }
});

// =============================
// GET EDIT DATA
// =============================
async function getEdit() {
  block(document.getElementById("form-teacher"));

  try {
    // Ambil data guru
    const { data } = await ApiService.get("master/teachers", props.selected);
    teacher.value = data.teacher;

    // Ambil foto guru, format seperti user
    foto.value = data.teacher.foto ? ["/storage/" + data.teacher.foto] : [];

    // Ambil user terkait guru
    const res = await axios.post(`/master/users/by-teacher/${props.selected}`);
    if (res.data.exists && res.data.user) {
      const u = res.data.user;
      user.value = {
        id: u.uuid, // <-- pastikan ambil UUID
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
      // Guru belum punya user
      user.value = {
        id: null,
        username: "",
        email: teacher.value.email || "",
        name: teacher.value.nama,
        status: "Aktif",
        password: "",
        passwordConfirmation: "",
        role_id: "4",
        photo: [],
      };
    }

  } catch (e: any) {
    toast.error("Gagal mengambil data guru & user");
  } finally {
    unblock(document.getElementById("form-teacher"));
  }
}

// =============================
// SUBMIT
// =============================
async function submit() {
  block(document.getElementById("form-teacher"));

  try {
    // ==============================
    // STEP 1 — SAVE / UPDATE TEACHER
    // ==============================
    const teacherForm = new FormData();
    const teacherFields = [
      "nama", "email", "nip", "jenis_kelamin", "tempat_lahir",
      "tanggal_lahir", "no_telepon", "alamat", "level",
      "mata_pelajaran", "status",
    ];
    teacherFields.forEach(k => teacherForm.append(k, teacher.value[k] ?? ""));
    if (foto.value.length && foto.value[0].file) teacherForm.append("foto", foto.value[0].file);

    let teacherId = props.selected;
    if (!props.selected) {
      // CREATE teacher
      const resTeacher = await axios.post(`/master/teachers/store`, teacherForm);
      teacherId = resTeacher.data.teacher.id;
      props.selected = teacherId;
    } else {
      // UPDATE teacher
      teacherForm.append("_method", "PUT");
      await axios.post(`/master/teachers/${props.selected}`, teacherForm);
    }

    // ==============================
    // STEP 2 — SAVE / UPDATE USER
    // ==============================

    // Jika user tidak ingin membuat akun → lewati proses user
    if (!user.value.username) {
      toast.success("Berhasil menyimpan data guru");
      emit("close");
      emit("refresh");
      formRef.value.resetForm();
      return;
    }

    const userForm = new FormData();
    userForm.append("username", user.value.username);
    userForm.append("name", user.value.name);
    userForm.append("email", user.value.email || teacher.value.email);
    userForm.append("status", user.value.status);
    userForm.append("role_id", "4");
    userForm.append("teacher_id", teacherId);

    if (foto.value.length && foto.value[0].file) {
      userForm.append("photo", foto.value[0].file);
    }

    // Hanya kirim password jika user baru atau password diubah
    if (!user.value.id || user.value.password) {
      userForm.append("password", user.value.password);
      userForm.append("password_confirmation", user.value.passwordConfirmation);
    }

    if (!user.value.id) {
      await axios.post("/master/users/store", userForm);
    } else {
      userForm.append("_method", "PUT");
      await axios.post(`/master/users/${user.value.id}`, userForm);
    }

    toast.success("Berhasil menyimpan data guru & user");
    emit("close");
    emit("refresh");
    formRef.value.resetForm();

  } catch (e: any) {
    toast.error(e.response?.data?.message || "Gagal menyimpan data");
    if (e.response?.data?.errors && formRef.value) {
      formRef.value.setErrors(e.response.data.errors);
    }
  } finally {
    unblock(document.getElementById("form-teacher"));
  }
}

onMounted(() => {
  if (props.selected) getEdit();
});
</script>

<template>
  <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-teacher" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Guru</h2>
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

        <!-- Konfirmasi Password -->
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
              v-model="teacher.nama" placeholder="Masukkan Nama" />
            <ErrorMessage name="nama" class="text-danger small" />
          </div>
        </div>

        <!-- NIP -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">NIP</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="nip" v-model="teacher.nip"
              placeholder="Masukkan NIP" />
            <ErrorMessage name="nip" class="text-danger small" />
          </div>
        </div>

        <!-- Jenis Kelamin -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Jenis Kelamin</label>
            <Field as="select" name="jenis_kelamin" v-model="teacher.jenis_kelamin"
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
              v-model="teacher.tempat_lahir" placeholder="Masukkan Tempat Lahir" />
            <ErrorMessage name="tempat_lahir" class="text-danger small" />
          </div>
        </div>

        <!-- Tanggal Lahir -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tanggal Lahir</label>
            <Field class="form-control form-control-lg form-control-solid" type="date" name="tanggal_lahir"
              v-model="teacher.tanggal_lahir" />
            <ErrorMessage name="tanggal_lahir" class="text-danger small" />
          </div>
        </div>

        <!-- Email -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Email</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="email"
              v-model="teacher.email" placeholder="Masukkan Email" />
            <ErrorMessage name="email" class="text-danger small" />
          </div>
        </div>

        <!-- Nomor Telepon -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Nomor Telepon</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="no_telepon"
              v-model="teacher.no_telepon" placeholder="Masukkan Nomor Telepon" />
            <ErrorMessage name="no_telepon" class="text-danger small" />
          </div>
        </div>

        <!-- Alamat -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Alamat</label>
            <Field as="textarea" class="form-control form-control-lg form-control-solid" name="alamat"
              v-model="teacher.alamat" placeholder="Masukkan Alamat" />
            <ErrorMessage name="alamat" class="text-danger small" />
          </div>
        </div>

        <!-- level -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Level</label>
            <Field as="select" name="level" v-model="teacher.level"
              class="form-select form-select-lg form-select-solid">
              <option value="">Pilih Level</option>
              <option value="Kepala">Kepala</option>
              <option value="Wali Kelas">Wali Kelas</option>
              <option value="Guru">Guru</option>
            </Field>
            <ErrorMessage name="level" class="text-danger small" />
            <ErrorMessage name="level" class="text-danger small" />
          </div>
        </div>

        <!-- Mata Pelajaran -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Mata Pelajaran</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="mata_pelajaran"
              v-model="teacher.mata_pelajaran" placeholder="Masukkan Mata Pelajaran" />
            <ErrorMessage name="mata_pelajaran" class="text-danger small" />
          </div>
        </div>

        <!-- Status -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Status</label>
            <Field as="select" name="status" v-model="teacher.status"
              class="form-select form-select-lg form-select-solid">
              <option value="">Pilih Status</option>
              <option value="Aktif">Aktif</option>
              <option value="Tidak Aktif">Tidak Aktif</option>
              <option value="Cuti">Cuti</option>
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
      </div>
    </div>

    <div class="card-footer d-flex">
      <button type="submit" class="btn btn-primary btn-sm ms-auto">
        Simpan
      </button>
    </div>
  </VForm>
</template>