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
    text: item.nama,
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
    text: `${item.tahun_ajaran} - ${item.semester}`,
  }))
);

const formSchema = Yup.object().shape({
  siswa_id: Yup.string().required("Siswa harus dipilih"),
  jenis_pembayaran_id: Yup.string().required("Jenis pembayaran harus dipilih"),
  tahun_ajaran_id: Yup.string().required("Tahun ajaran harus dipilih"),
  total: Yup.number().required("Total harus diisi").min(1, "Minimal 1"),
  tanggal_tagih: Yup.date().required("Tanggal tagih harus diisi"),
  keterangan: Yup.string().nullable(),
});

function getEdit() {
  block(document.getElementById("form-bill"));
  ApiService.get("master/bills", props.selected)
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
  formData.append("siswa_id", bill.value.siswa_id);
  formData.append("jenis_pembayaran_id", bill.value.jenis_pembayaran_id);
  formData.append("tahun_ajaran_id", bill.value.tahun_ajaran_id);
  formData.append("total", bill.value.total);
  formData.append("tanggal_tagih", bill.value.tanggal_tagih);
  formData.append("keterangan", bill.value.keterangan ?? "");

  if (props.selected) {
    formData.append("_method", "PUT");
  }

  block(document.getElementById("form-bill"));
  axios({
    method: "post",
    url: props.selected
      ? `/master/bills/${props.selected}`
      : "/master/bills/store",
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
  <VForm
    class="form card mb-10"
    @submit="submit"
    :validation-schema="formSchema"
    id="form-bill"
    ref="formRef"
  >
    <div class="card-header align-items-center">
      <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Tagihan</h2>
      <button
        type="button"
        class="btn btn-sm btn-light-danger ms-auto"
        @click="emit('close')"
      >
        Batal
        <i class="la la-times-circle p-0"></i>
      </button>
    </div>

    <div class="card-body">
      <div class="row">
        <!-- Siswa -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Siswa</label>
            <Field name="siswa_id" type="hidden" v-model="bill.siswa_id">
              <select2
                placeholder="Pilih siswa"
                class="form-select-solid"
                :options="students"
                name="siswa_id"
                v-model="bill.siswa_id"
              />
            </Field>
            <ErrorMessage name="siswa_id" class="text-danger small" />
          </div>
        </div>

        <!-- Jenis Pembayaran -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Jenis Pembayaran</label>
            <Field
              name="jenis_pembayaran_id"
              type="hidden"
              v-model="bill.jenis_pembayaran_id"
            >
              <select2
                placeholder="Pilih jenis pembayaran"
                class="form-select-solid"
                :options="paymentTypes"
                name="jenis_pembayaran_id"
                v-model="bill.jenis_pembayaran_id"
              />
            </Field>
            <ErrorMessage name="jenis_pembayaran_id" class="text-danger small" />
          </div>
        </div>

        <!-- Tahun Ajaran -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tahun Ajaran</label>
            <Field name="tahun_ajaran_id" type="hidden" v-model="bill.tahun_ajaran_id">
              <select2
                placeholder="Pilih tahun ajaran"
                class="form-select-solid"
                :options="schoolYears"
                name="tahun_ajaran_id"
                v-model="bill.tahun_ajaran_id"
              />
            </Field>
            <ErrorMessage name="tahun_ajaran_id" class="text-danger small" />
          </div>
        </div>

        <!-- Total -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Total</label>
            <Field
              class="form-control form-control-lg form-control-solid"
              type="number"
              name="total"
              v-model="bill.total"
              placeholder="Masukkan total tagihan"
            />
            <ErrorMessage name="total" class="text-danger small" />
          </div>
        </div>

        <!-- Tanggal Tagih -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tanggal Tagih</label>
            <Field
              class="form-control form-control-lg form-control-solid"
              type="date"
              name="tanggal_tagih"
              v-model="bill.tanggal_tagih"
            />
            <ErrorMessage name="tanggal_tagih" class="text-danger small" />
          </div>
        </div>

        <!-- Keterangan -->
        <div class="col-md-8">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Keterangan</label>
            <Field
              as="textarea"
              class="form-control form-control-lg form-control-solid"
              name="keterangan"
              v-model="bill.keterangan"
              placeholder="Masukkan keterangan"
            />
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
