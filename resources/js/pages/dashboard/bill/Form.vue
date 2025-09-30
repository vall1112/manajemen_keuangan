<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Student, PaymentType, SchoolYear } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useStudent } from "@/services/useStudent";
import { usePaymentType } from "@/services/usePaymentType";
import { useSchoolYear } from "@/services/useSchoolYear";

const props = defineProps({
  selected: {
    type: String,
    default: null,
  },
});

const emit = defineEmits(["close", "refresh"]);

const bill = ref<any>({});
const formRef = ref();

const student = useStudent();
const students = computed(() =>
  student.data.value?.map((item: Student) => ({
    id: item.id,
    text: `${item.nama} - ${item.classroom?.nama_kelas ?? ''}`,
  }))
);

const paymentType = usePaymentType();
const paymentTypes = computed(() =>
  paymentType.data.value?.map((item: PaymentType) => ({
    id: item.id,
    text: item.nama_jenis,
  }))
);

const schoolYear = useSchoolYear();
const schoolYears = computed(() =>
  schoolYear.data.value?.map((item: SchoolYear) => ({
    id: item.id,
    text: item.tahun_ajaran,
  }))
);

const formSchema = Yup.object().shape({
  student_id: Yup.string().required("Siswa harus dipilih"),
  payment_type_id: Yup.string().required("Jenis pembayaran harus dipilih"),
  school_year_id: Yup.string().required("Tahun ajaran harus dipilih"),
  total_tagihan: Yup.number().required("Total tagihan harus diisi").min(1, "Minimal 1"),
  tanggal_tagih: Yup.date().required("Tanggal tagih harus diisi"),
  jatuh_tempo: Yup.date()
    .required("Jatuh tempo harus diisi")
    .min(Yup.ref("tanggal_tagih"), "Jatuh tempo tidak boleh sebelum tanggal tagih"),
  keterangan: Yup.string().nullable(),
});

function getEdit() {
  block(document.getElementById("form-bill"));
  ApiService.get("/bills", props.selected)
    .then(({ data }) => {
      bill.value = data.bill;
    })
    .catch((err: any) => {
      toast.error(err.response.data.message);
    })
    .finally(() => {
      unblock(document.getElementById("form-bill"));
    });
}

function submit() {
  const formData = new FormData();
  formData.append("student_id", bill.value.student_id);
  formData.append("payment_type_id", bill.value.payment_type_id);
  formData.append("school_year_id", bill.value.school_year_id);
  formData.append("total_tagihan", bill.value.total_tagihan);
  formData.append("tanggal_tagih", bill.value.tanggal_tagih);
  formData.append("jatuh_tempo", bill.value.jatuh_tempo);
  formData.append("keterangan", bill.value.keterangan ?? "");

  if (props.selected) {
    formData.append("_method", "PUT");
  }

  block(document.getElementById("form-bill"));
  axios({
    method: "post",
    url: props.selected
      ? `/bills/${props.selected}`
      : "/bills/store",
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
      unblock(document.getElementById("form-bill"));
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
  <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-bill" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Tagihan</h2>
      <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
        Batal
        <i class="la la-times-circle p-0"></i>
      </button>
    </div>

    <div class="card-body">
      <div class="row">
        <!-- Siswa -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Nama Siswa</label>
            <Field name="student_id" type="hidden" v-model="bill.student_id">
              <select2 placeholder="Pilih siswa" class="form-select-solid" :options="students" name="student_id"
                v-model="bill.student_id" />
            </Field>
            <ErrorMessage name="student_id" class="text-danger small" />
          </div>
        </div>

        <!-- Jenis Pembayaran -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Jenis Pembayaran</label>
            <Field name="payment_type_id" type="hidden" v-model="bill.payment_type_id">
              <select2 placeholder="Pilih jenis pembayaran" class="form-select-solid" :options="paymentTypes"
                name="payment_type_id" v-model="bill.payment_type_id" />
            </Field>
            <ErrorMessage name="payment_type_id" class="text-danger small" />
          </div>
        </div>

        <!-- Tahun Ajaran -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tahun Ajaran</label>
            <Field name="school_year_id" type="hidden" v-model="bill.school_year_id">
              <select2 placeholder="Pilih tahun ajaran" class="form-select-solid" :options="schoolYears"
                name="school_year_id" v-model="bill.school_year_id" />
            </Field>
            <ErrorMessage name="school_year_id" class="text-danger small" />
          </div>
        </div>

        <!-- Total Tagihan-->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Total Tagihan</label>
            <Field class="form-control form-control-lg form-control-solid" type="number" name="total_tagihan"
              v-model="bill.total_tagihan" placeholder="Masukkan total tagihan" />
            <ErrorMessage name="total_tagihan" class="text-danger small" />
          </div>
        </div>

        <!-- Tanggal Tagih -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tanggal Tagih</label>
            <Field class="form-control form-control-lg form-control-solid" type="date" name="tanggal_tagih"
              v-model="bill.tanggal_tagih" />
            <ErrorMessage name="tanggal_tagih" class="text-danger small" />
          </div>
        </div>

        <!-- Jatuh Tempo -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Jatuh Tempo</label>
            <Field class="form-control form-control-lg form-control-solid" type="date" name="jatuh_tempo"
              v-model="bill.jatuh_tempo" />
            <ErrorMessage name="jatuh_tempo" class="text-danger small" />
          </div>
        </div>

        <!-- Keterangan -->
        <div class="col-md-8">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Keterangan</label>
            <Field as="textarea" class="form-control form-control-lg form-control-solid" name="keterangan"
              v-model="bill.keterangan" placeholder="Masukkan keterangan" />
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
