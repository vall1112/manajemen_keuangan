<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import ApiService from "@/core/services/ApiService";
import { useStudent } from "@/services/useStudent";
import { useSchoolYear } from "@/services/useSchoolYear";
import { usePaymentType } from "@/services/usePaymentType";

const props = defineProps({
  selected: {
    type: String,
    default: null,
  },
});

const emit = defineEmits(["close", "refresh"]);

const bill = ref<any>({});
const formRef = ref();

const formSchema = Yup.object().shape({
  siswa_id: Yup.string().required("Pilih siswa"),
  jenis_pembayaran_id: Yup.string().required("Pilih jenis pembayaran"),
  school_year_id: Yup.string().required("Pilih tahun ajaran"),
  total: Yup.number().required("Total tagihan harus diisi"),
  tanggal_tagih: Yup.string().required("Tanggal tagih harus diisi"),
  status: Yup.string().required("Status harus dipilih"),
  dibayar: Yup.number().nullable(),
  // sisa: Yup.number().nullable(),
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
  formData.append("school_year_id", bill.value.school_year_id);
  formData.append("total", bill.value.total);
  formData.append("tanggal_tagih", bill.value.tanggal_tagih);
  formData.append("status", bill.value.status);
  // formData.append("dibayar", bill.value.dibayar);
  // formData.append("sisa", bill.value.sisa);
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

// dropdown siswa
const student = useStudent();
const students = computed(() =>
  student.data.value?.map((item: any) => ({
    id: item.id,
    text: item.nama,
  }))
);

// dropdown tahun ajaran
const schoolYear = useSchoolYear();
const schoolYears = computed(() =>
  schoolYear.data.value?.map((item: any) => ({
    id: item.id,
    text: `${item.tahun_ajaran} - Semester ${item.semester}`, 
  }))
);

// dropdown jenis pembayaran
const paymentType = usePaymentType();
const paymentTypes = computed(() =>
  paymentType.data.value?.map((item: any) => ({
    id: item.id,
    text: item.nama_jenis,
  }))
);

onMounted(async () => {
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
        <!-- siswa -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Siswa</label>
            <Field name="siswa_id" type="hidden" v-model="bill.siswa_id">
              <select2
                placeholder="Pilih Siswa"
                class="form-select-solid"
                :options="students"
                name="siswa_id"
                v-model="bill.siswa_id"
              />
            </Field>
            <ErrorMessage name="siswa_id" class="text-danger" />
          </div>
        </div>

        <!-- jenis pembayaran -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Jenis Pembayaran</label>
            <Field
              name="jenis_pembayaran_id"
              type="hidden"
              v-model="bill.jenis_pembayaran_id"
            >
              <select2
                placeholder="Pilih Jenis Pembayaran"
                class="form-select-solid"
                :options="paymentTypes"
                name="jenis_pembayaran_id"
                v-model="bill.jenis_pembayaran_id"
              />
            </Field>
            <ErrorMessage name="jenis_pembayaran_id" class="text-danger" />
          </div>
        </div>

        <!-- tahun ajaran -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tahun Ajaran</label>
            <Field name="school_year_id" type="hidden" v-model="bill.school_year_id">
              <select2
                placeholder="Pilih Tahun Ajaran"
                class="form-select-solid"
                :options="schoolYears"
                name="school_year_id"
                v-model="bill.school_year_id"
              />
            </Field>
            <ErrorMessage name="school_year_id" class="text-danger" />
          </div>
        </div>

        <!-- total -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Total</label>
            <Field
              class="form-control form-control-lg form-control-solid"
              type="number"
              name="total"
              v-model="bill.total"
              placeholder="Masukkan Total Tagihan"
            />
            <ErrorMessage name="total" class="text-danger" />
          </div>
        </div>

        <!-- tanggal tagih -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tanggal Tagih</label>
            <Field
              class="form-control form-control-lg form-control-solid"
              type="date"
              name="tanggal_tagih"
              v-model="bill.tanggal_tagih"
            />
            <ErrorMessage name="tanggal_tagih" class="text-danger" />
          </div>
        </div>

        <!-- status
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Status</label>
            <Field name="status" as="select" v-model="bill.status" class="form-select-solid">
              <option value="">Pilih Status</option>
              <option value="Belum Dibayar">Belum Dibayar</option>
              <option value="Dibayar Sebagian">Dibayar Sebagian</option>
              <option value="Lunas">Lunas</option>
            </Field>
            <ErrorMessage name="status" class="text-danger" />
          </div>
        </div> -->

        <!-- dibayar -->
        <!-- <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Dibayar</label>
            <Field
              class="form-control form-control-lg form-control-solid"
              type="number"
              name="dibayar"
              v-model="bill.dibayar"
              placeholder="Masukkan Jumlah Dibayar"
            />
            <ErrorMessage name="dibayar" class="text-danger" />
          </div>
        </div> -->

        <!-- sisa -->
        <!-- <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Sisa</label>
            <Field
              class="form-control form-control-lg form-control-solid"
              type="number"
              name="sisa"
              v-model="bill.sisa"
              placeholder="Masukkan Sisa Tagihan"
            />
            <ErrorMessage name="sisa" class="text-danger" />
          </div>
        </div> -->

        <!-- keterangan -->
        <div class="col-md-12">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Keterangan</label>
            <Field
              class="form-control form-control-lg form-control-solid"
              type="text"
              name="keterangan"
              v-model="bill.keterangan"
              placeholder="Masukkan Keterangan"
            />
            <ErrorMessage name="keterangan" class="text-danger" />
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
