<script setup lang="ts">
import { ref, watch, onMounted, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import { block, unblock } from "@/libs/utils";
import { useRouter, useRoute } from "vue-router";

const route = useRoute();
const router = useRouter();

const props = defineProps({
  selected: { type: String, default: null },
});

const emit = defineEmits(["close", "refresh"]);

function formatRupiah(value: number) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(value);
}

const nominalDisplay = computed({
  get() {
    if (!transaction.value.total_tagihan) return "";
    return formatRupiah(transaction.value.total_tagihan);
  },
  set(val: string) {
    const number = parseInt(val.replace(/\D/g, "")) || 0;
    transaction.value.total_tagihan = number;
  },
});

const kodeTagihan = ref("");
const transaction = ref<any>({
  bill_id: null,
  student_id: null, // ✅ tambahkan ini
  student_name: "",
  payment_type_name: "",
  metode_pembayaran: "",
  total_tagihan: 0,
  status: "Berhasil",
  catatan: "",
});


const saldoTabungan = ref<number | null>(null);

onMounted(() => {
  if (route.query.kode) {
    kodeTagihan.value = String(route.query.kode);
  }
});

const formRef = ref();

// Validasi Yup
const formSchema = Yup.object().shape({
  kodeTagihan: Yup.string().required("Kode tagihan harus diisi"),
  student_name: Yup.string().required("Nama siswa harus ada"),
  payment_type_name: Yup.string().required("Jenis pembayaran harus ada"),
  catatan: Yup.string(),
});

// Watch kodeTagihan untuk fetch data
watch(kodeTagihan, async (newVal) => {
  if (!newVal) return;

  try {
    const { data } = await axios.get(`/bills/code/${newVal}`);

    if (data.status === "Lunas") {
      toast.info("Tagihan sudah dibayar / lunas");
      transaction.value = {
        bill_id: null,
        student_name: "",
        payment_type_name: "",
        metode_pembayaran: "",
        total_tagihan: 0,
        status: "Berhasil",
        catatan: "",
      };
      return;
    }

    transaction.value.bill_id = data.id;
    transaction.value.student_id = data.student_id;
    transaction.value.student_name = data.student_name;
    transaction.value.payment_type_name = data.payment_type_name;
    transaction.value.total_tagihan = data.total_tagihan;
    transaction.value.metode_pembayaran = "Pembayaran melalui tabungan";

  } catch (err: any) {
    toast.error(err.response?.data?.message || "Tagihan tidak ditemukan");
    transaction.value = {
      bill_id: null,
      student_name: "",
      payment_type_name: "",
      metode_pembayaran: "",
      total_tagihan: 0,
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
    nominal: transaction.value.total_tagihan,
    status: transaction.value.status,
    catatan: transaction.value.catatan,
    metode_pembayaran: transaction.value.metode_pembayaran, // ✅ kirim ke API
  };

  block(document.getElementById("form-transaction"));
  axios
    .post("/transactions/student/store", payload)
    .then(() => {
      toast.success("Transaksi berhasil disimpan");
      emit("refresh");
      emit("close");
      formRef.value.resetForm();

      setTimeout(() => {
        router.push({ name: "student.bill" });
      }, 2000);
    })
    .catch((err: any) => {
      toast.error(err.response?.data?.message || "Terjadi kesalahan");
    })
    .finally(() => {
      unblock(document.getElementById("form-transaction"));
    });
}

// Watcher untuk metode pembayaran
watch(() => transaction.value.metode_pembayaran, async (newVal) => {
  console.log("Metode pembayaran:", newVal);
  console.log("Student ID:", transaction.value.student_id);

  if (newVal === "Pembayaran melalui tabungan") {
    if (!transaction.value.student_id) {
      toast.error("Student ID belum tersedia, silakan pilih tagihan terlebih dahulu");
      return;
    }

    try {
      const { data } = await axios.get(`/saving-balances/${transaction.value.student_id}`);
      saldoTabungan.value = data.data.balance; // ✅ simpan di variabel saldoTabungan
    } catch (err: any) {
      toast.error("Gagal mengambil saldo tabungan");
    }
  }
});
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
              v-model="kodeTagihan" readonly />
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

        <!-- Metode Pembayaran -->
        <div class="col-md-4" v-if="transaction.bill_id">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Metode Pembayaran</label>

            <input type="text" class="form-control form-control-lg form-control-solid"
              v-model="transaction.metode_pembayaran" readonly />

            <ErrorMessage name="metode_pembayaran" class="text-danger mt-2" />
          </div>
        </div>

        <!-- Saldo Tabungan Anda -->
        <div class="col-md-4"
          v-if="transaction.bill_id && transaction.metode_pembayaran === 'Pembayaran melalui tabungan'">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Saldo Tabungan Anda</label>
            <input type="text" class="form-control form-control-lg form-control-solid"
              :value="saldoTabungan !== null ? formatRupiah(saldoTabungan) : '-'" readonly />
          </div>
        </div>


        <!-- Total Bayar -->
        <div class="col-md-4" v-if="transaction.bill_id">
          <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6 required">Total Bayar</label>
            <Field type="text" class="form-control form-control-lg form-control-solid" name="total_tagihan"
              v-model="nominalDisplay" readonly />
            <ErrorMessage name="total_tagihan" class="text-danger" />
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
      <button type="submit" class="btn btn-primary btn-sm ms-auto">Bayar</button>
    </div>
  </VForm>
</template>
