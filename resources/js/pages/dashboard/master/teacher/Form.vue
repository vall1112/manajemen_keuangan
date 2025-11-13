<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Teacher } from "@/types";
import ApiService from "@/core/services/ApiService";

const props = defineProps({
  selected: {
    type: String,
    default: null,
  },
});

const emit = defineEmits(["close", "refresh"]);

const teacher = ref<Teacher>({} as Teacher);
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const foto = ref<any>([]);
const formRef = ref();

const formSchema = Yup.object().shape({
  nama: Yup.string().required("Nama harus diisi"),
  email: Yup.string().email("Email harus valid").nullable(),
  no_telepon: Yup.string().required("Nomor Telepon harus diisi"),
  nip: Yup.string().nullable(),
  tempat_lahir: Yup.string().required("Tempat lahir harus diisi"),
  tanggal_lahir: Yup.date().required("Tanggal lahir harus diisi"),
  jenis_kelamin: Yup.string().required("Pilih jenis kelamin"),
  alamat: Yup.string().required("Alamat harus diisi"),
  mata_pelajaran: Yup.string().nullable(),
  jabatan: Yup.string().required("Jabatan harus diisi"),
  status: Yup.string().required("Status harus dipilih"),
});

function getEdit() {
  block(document.getElementById("form-teacher"));
  ApiService.get("master/teachers", props.selected)
    .then(({ data }) => {
      teacher.value = data.teacher;
      foto.value = data.teacher.foto ? ["/storage/" + data.teacher.foto] : [];
    })
    .catch((err: any) => {
      toast.error(err.response.data.message);
    })
    .finally(() => {
      unblock(document.getElementById("form-teacher"));
    });
}

function submit() {
  const formData = new FormData();
  formData.append("nama", teacher.value.nama);
  formData.append("email", teacher.value.email ?? "");
  formData.append("no_telepon", teacher.value.no_telepon);
  formData.append("nip", teacher.value.nip ?? "");
  formData.append("tempat_lahir", teacher.value.tempat_lahir);
  formData.append("tanggal_lahir", teacher.value.tanggal_lahir);
  formData.append("jenis_kelamin", teacher.value.jenis_kelamin);
  formData.append("alamat", teacher.value.alamat);
  formData.append("jabatan", teacher.value.jabatan);
  formData.append("mata_pelajaran", teacher.value.mata_pelajaran);
  formData.append("status", teacher.value.status);

  if (foto.value.length) {
    formData.append("foto", foto.value[0].file);
  }
  if (props.selected) {
    formData.append("_method", "PUT");
  }

  block(document.getElementById("form-teacher"));
  axios({
    method: "post",
    url: props.selected
      ? `/master/teachers/${props.selected}`
      : "/master/teachers/store",
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
      unblock(document.getElementById("form-teacher"));
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

        <!-- Jabatan -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Jabatan</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="jabatan"
              v-model="teacher.jabatan" placeholder="Masukkan Jabatan" />
            <ErrorMessage name="jabatan" class="text-danger small" />
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
      <button type="submit" class="btn btn-primary btn-sm ms-auto">Simpan</button>
    </div>
  </VForm>
</template>