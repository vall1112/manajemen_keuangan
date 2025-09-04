<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Classroom, User } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useClassroom } from "@/services/useClassroom";
import { useUser } from "@/services/useUser";

const props = defineProps({
  selected: {
    type: String,
    default: null,
  },
});

const emit = defineEmits(["close", "refresh"]);

const student = ref<any>({});
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const foto = ref<any>([]);
const formRef = ref();

const classroom = useClassroom();
const classrooms = computed(() =>
  classroom.data.value?.map((item: Classroom) => ({
    id: item.id,
    text: item.nama_kelas,
  }))
);

const user = useUser();
const users = computed(() =>
  user.data.value?.map((item: User) => ({
    id: item.id,
    text: item.username,
  }))
);

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
  nama_ayah: Yup.string().nullable(),
  telepon_ayah: Yup.string().nullable(),
  nama_ibu: Yup.string().nullable(),
  telepon_ibu: Yup.string().nullable(),
});

function getEdit() {
  block(document.getElementById("form-student"));
  ApiService.get("master/students", props.selected)
    .then(({ data }) => {
      student.value = data.student;
      foto.value = data.student.foto ? ["/storage/" + data.student.foto] : [];
    })
    .catch((err: any) => {
      toast.error(err.response.data.message);
    })
    .finally(() => {
      unblock(document.getElementById("form-student"));
    });
}

function submit() {
  const formData = new FormData();
  formData.append("nis", student.value.nis);
  formData.append("nama", student.value.nama);
  formData.append("email", student.value.email ?? "");
  formData.append("telepon", student.value.telepon ?? "");
  formData.append("tempat_lahir", student.value.tempat_lahir);
  formData.append("tanggal_lahir", student.value.tanggal_lahir);
  formData.append("jenis_kelamin", student.value.jenis_kelamin);
  formData.append("alamat", student.value.alamat);
  formData.append("classroom_id", student.value.classroom_id);
  formData.append("status", student.value.status);
  formData.append("nama_ayah", student.value.nama_ayah ?? "");
  formData.append("telepon_ayah", student.value.telepon_ayah ?? "");
  formData.append("nama_ibu", student.value.nama_ibu ?? "");
  formData.append("telepon_ibu", student.value.telepon_ibu ?? "");

  if (foto.value.length) {
    formData.append("foto", foto.value[0].file);
  }
  if (props.selected) {
    formData.append("_method", "PUT");
  }

  block(document.getElementById("form-student"));
  axios({
    method: "post",
    url: props.selected
      ? `/master/students/${props.selected}`
      : "/master/students/store",
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
      formRef.value.setErrors(err.response.data.errors);
      toast.error(err.response.data.message);
    })
    .finally(() => {
      unblock(document.getElementById("form-student"));
    });
}

onMounted(() => {
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
            <label class="form-label fw-bold fs-6 required">Telepon</label>
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

        <!-- Classroom ID -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">
              Kelas
            </label>
            <Field name="classroom_id" type="hidden" v-model="student.classroom_id">
              <select2 placeholder="Pilih Kelas" class="form-select-solid" :options="classrooms" name="classroom_id"
                v-model="student.classroom_id">
              </select2>
            </Field>
            <ErrorMessage name="classroom_id" class="text-danger" />
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
