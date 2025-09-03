<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import ApiService from "@/core/services/ApiService";
import { useBill } from "@/services/useBill"; // service ambil data bill

const props = defineProps({
  selected: {
    type: String,
    default: null,
  },
});

const emit = defineEmits(["close", "refresh"]);

const transaction = ref<any>({});
const formRef = ref();

const formSchema = Yup.object().shape({
  bill_id: Yup.string().required("Pilih tagihan"),
  nominal: Yup.number().required("Nominal harus diisi"),
  metode: Yup.string().required("Metode pembayaran harus diisi"),
  bukti: Yup.mixed().nullable(),
  status: Yup.string().required("Status harus diisi"),
  keterangan: Yup.string().nullable(),
});

function getEdit() {
  block(document.getElementById("form-transaction"));
  ApiService.get("transactions", props.selected)
    .then(({ data }) => {
      transaction.value = data.transaction;
    })
    .catch((err: any) => {
      toast.error(err.response.data.message);
    })
    .finally(() => {
      unblock(document.getElementById("form-transaction"));
    });
}

function submit() {
  const formData = new FormData();
  formData.append("bill_id", transaction.value.bill_id);
  formData.append("nominal", transaction.value.nominal);
  formData.append("metode", transaction.value.metode);
  if (transaction.value.bukti instanceof File) {
    formData.append("bukti", transaction.value.bukti);
  }
  formData.append("status", transaction.value.status);
  formData.append("keterangan", transaction.value.keterangan ?? "");

  if (props.selected) {
    formData.append("_method", "PUT");
  }

  block(document.getElementById("form-transaction"));
  axios({
    method: "post",
    url: props.selected
      ? `/transactions/${props.selected}`
      : "/transactions/store",
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
      unblock(document.getElementById("form-transaction"));
    });
}

// dropdown tagihan
const bill = useBill();
const bills = computed(() =>
  bill.data.value?.map((item: any) => ({
    id: item.id,
    text: `Tagihan #${item.id} - ${item.total}`,
  }))
);

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
  <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-transaction" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Transaksi</h2>
      <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
        Batal
        <i class="la la-times-circle p-0"></i>
      </button>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Tagihan -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Tagihan</label>
            <Field name="bill_id" type="hidden" v-model="transaction.bill_id">
              <select2 placeholder="Pilih Tagihan" class="form-select-solid" :options="bills" name="bill_id"
                v-model="transaction.bill_id" />
            </Field>
            <ErrorMessage name="bill_id" class="text-danger" />
          </div>
        </div>

        <!-- Nominal -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Nominal</label>
            <Field class="form-control form-control-lg form-control-solid" type="number" name="nominal"
              v-model="transaction.nominal" placeholder="Masukkan Nominal" />
            <ErrorMessage name="nominal" class="text-danger" />
          </div>
        </div>

        <!-- Metode -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Metode</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="metode"
              v-model="transaction.metode" placeholder="Masukkan Metode Pembayaran" />
            <ErrorMessage name="metode" class="text-danger" />
          </div>
        </div>

        <!-- Bukti -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Bukti Pembayaran</label>
            <input class="form-control form-control-lg form-control-solid" type="file" name="bukti"
              @change="(e: any) => (transaction.bukti = e.target.files[0])" />
            <ErrorMessage name="bukti" class="text-danger" />
          </div>
        </div>

        <!-- Status -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Status</label>
            <Field as="select" name="status" v-model="transaction.status"
              class="form-select form-select-lg form-select-solid">
              <option value="">Pilih Status</option>
              <option value="Pending">Pending</option>
              <option value="Berhasil">Berhasil</option>
              <option value="Gagal">Gagal</option>
            </Field>
            <ErrorMessage name="status" class="text-danger small" />
          </div>
        </div>

        <!-- Keterangan -->
        <div class="col-md-6">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Keterangan</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="keterangan"
              v-model="transaction.keterangan" placeholder="Masukkan Keterangan" />
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
