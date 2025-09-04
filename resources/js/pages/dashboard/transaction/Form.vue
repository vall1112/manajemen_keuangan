<script setup lang="ts">
import { ref, watch } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import { block, unblock } from "@/libs/utils";
import { useRouter } from 'vue-router';

const router = useRouter();

const props = defineProps({
  selected: { type: String, default: null },
});

const emit = defineEmits(["close", "refresh"]);

const kodeTagihan = ref("");
const transaction = ref<any>({
  bill_id: null,
  student_name: "",
  payment_type_name: "",
  total: 0,
  status: "Berhasil", // hanya di script
  catatan: "",
});

const formRef = ref();

// Validasi Yup
const formSchema = Yup.object().shape({
  kodeTagihan: Yup.string().required("Kode tagihan harus diisi"),
  student_name: Yup.string().required("Nama siswa harus ada"),
  payment_type_name: Yup.string().required("Jenis pembayaran harus ada"),
  total: Yup.number().required("Total bayar harus ada"),
  catatan: Yup.string(),
});

// Watch kodeTagihan untuk fetch data
watch(kodeTagihan, async (newVal) => {
  if (!newVal) return;

  try {
    const { data } = await axios.get(`/bills/code/${newVal}`);

    if (data.status === 'Lunas') {
      toast.info('Tagihan sudah dibayar / lunas');
      transaction.value = {
        bill_id: null,
        student_name: "",
        payment_type_name: "",
        total: 0,
        status: "Berhasil",
        catatan: "",
      };
      return;
    }

    transaction.value.bill_id = data.id;
    transaction.value.student_name = data.student_name;
    transaction.value.payment_type_name = data.payment_type_name;
    transaction.value.total = data.total;

  } catch (err: any) {
    toast.error(err.response?.data?.message || "Tagihan tidak ditemukan");
    transaction.value = {
      bill_id: null,
      student_name: "",
      payment_type_name: "",
      total: 0,
      status: "Berhasil",
      catatan: "",
    };
  }
});

// Submit form
function submit() {
  if (!transaction.value.bill_id) {
    toast.error("Tidak ada transaksi yang dipilih");
    return;
  }

  const payload = {
    bill_id: transaction.value.bill_id,
    nominal: transaction.value.total,
    status: transaction.value.status,
    catatan: transaction.value.catatan,
  };

  block(document.getElementById("form-transaction"));
  axios
    .post("/transactions/store", payload)
    .then(() => {
      toast.success("Transaksi berhasil disimpan");
      emit("refresh");
      emit("close");
      formRef.value.resetForm();
    })
    .catch((err: any) => {
      toast.error(err.response?.data?.message || "Terjadi kesalahan");
    })
    .finally(() => {
      unblock(document.getElementById("form-transaction"));
    });
}
</script>

<template>
  <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-transaction" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">Tambah Transaksi</h2>
      <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="router.back()">
        Batal
        <i class="la la-times-circle p-0"></i>
      </button>
    </div>
    <div class="card-body">
      <div class="row">

        <!-- Kode Tagihan -->
        <div class="col-md-4">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Kode Tagihan</label>
            <Field type="text" class="form-control form-control-lg form-control-solid" name="kodeTagihan"
              v-model="kodeTagihan" placeholder="Masukkan kode tagihan" />
            <ErrorMessage name="kodeTagihan" class="text-danger" />
          </div>
        </div>

        <!-- Nama Siswa -->
        <div class="col-md-4" v-if="transaction.bill_id">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Nama Siswa</label>
            <Field type="text" class="form-control form-control-lg form-control-solid" name="student_name"
              v-model="transaction.student_name" readonly />
            <ErrorMessage name="student_name" class="text-danger" />
          </div>
        </div>

        <!-- Jenis Pembayaran -->
        <div class="col-md-4" v-if="transaction.bill_id">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Jenis Pembayaran</label>
            <Field type="text" class="form-control form-control-lg form-control-solid" name="payment_type_name"
              v-model="transaction.payment_type_name" readonly />
            <ErrorMessage name="payment_type_name" class="text-danger" />
          </div>
        </div>

        <!-- Total Bayar (Nominal) -->
        <div class="col-md-4" v-if="transaction.bill_id">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Nominal</label>
            <Field type="number" class="form-control form-control-lg form-control-solid" name="total"
              v-model="transaction.total" readonly />
            <ErrorMessage name="total" class="text-danger" />
          </div>
        </div>

        <!-- Catatan -->
        <div class="col-md-4" v-if="transaction.bill_id">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Catatan</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="catatan"
              v-model="transaction.catatan" placeholder="Masukkan catatan" />
            <ErrorMessage name="catatan" class="text-danger" />
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
