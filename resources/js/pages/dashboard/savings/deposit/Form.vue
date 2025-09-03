<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Student } from "@/types";
import { useStudent } from "@/services/useStudent";

const emit = defineEmits(["close", "refresh"]);

const saving = ref<any>({});
const formRef = ref();

const student = useStudent();
const students = computed(() =>
  student.data.value?.map((item: Student) => ({
    id: item.id,
    text: `${item.nama} - ${item.classroom?.nama_kelas ?? ''}`,
    nama: item.nama,
    nama_kelas: item.classroom?.nama_kelas ?? null,
  }))
);

const formSchema = Yup.object().shape({
  student_id: Yup.string().required("Siswa harus dipilih"),
  nominal: Yup.number().required("Nominal harus diisi").min(1, "Minimal 1"),
  keterangan: Yup.string().nullable(),
});

function submit() {
  const formData = new FormData();
  formData.append("student_id", saving.value.student_id);
  formData.append("nominal", saving.value.nominal);
  formData.append("jenis", "Setor");
  formData.append("keterangan", saving.value.keterangan ?? "");

  block(document.getElementById("form-saving"));
  axios.post("/savings/deposits/store", formData, {
    headers: { "Content-Type": "multipart/form-data" },
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
      unblock(document.getElementById("form-saving"));
    });
}
</script>

<template>
  <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-saving" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">Setor Tabungan</h2>
    </div>

    <div class="card-body">
      <div class="row">
        <!-- Siswa -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Nama Siswa</label>
            <Field name="student_id" type="hidden" v-model="saving.student_id">
              <select2 placeholder="Pilih siswa" class="form-select-solid" :options="students"
                v-model="saving.student_id" />
            </Field>
            <ErrorMessage name="student_id" class="text-danger small" />
          </div>
        </div>

        <!-- Nominal -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Nominal</label>
            <Field class="form-control form-control-lg form-control-solid" type="number" name="nominal"
              v-model="saving.nominal" placeholder="Masukkan nominal" />
            <ErrorMessage name="nominal" class="text-danger small" />
          </div>
        </div>

        <!-- Keterangan -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Keterangan</label>
            <Field as="textarea" class="form-control form-control-lg form-control-solid" name="keterangan"
              v-model="saving.keterangan" placeholder="Masukkan keterangan" />
            <ErrorMessage name="keterangan" class="text-danger small" />
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
