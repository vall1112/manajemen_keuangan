<script setup lang="ts">
import { h, ref, watch, computed } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { toast } from "vue3-toastify";
import axios from "axios";

// Tipe data siswa + tunggakan
interface StudentDebt {
  id: number;
  nis: string;
  name: string;
  class_name: string;
  total_debt: number;        // total tunggakan dalam rupiah
  unpaid_months: string[];   // contoh: ["Januari 2025", "Februari 2025"]
  phone: string;
  photo?: string;
}

const columnHelper = createColumnHelper<StudentDebt>();
const paginateRef = ref<any>(null);
const openDetail = ref<boolean>(false);
const selectedStudent = ref<StudentDebt | null>(null);

// Fungsi format rupiah
const formatRupiah = (amount: number) => {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(amount);
};

// Buka modal detail tunggakan
const viewDetail = (student: StudentDebt) => {
  selectedStudent.value = student;
  openDetail.value = true;
};

// Cetak kwitansi tunggakan (contoh)
const printInvoice = async (studentId: number) => {
  try {
    const { data } = await axios.get(`/api/teacher/debts/${studentId}/invoice`, {
      responseType: "blob",
    });
    const url = window.URL.createObjectURL(new Blob([data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", `Kwitansi_Tunggakan_${studentId}.pdf`);
    document.body.appendChild(link);
    link.click();
    link.remove();
  } catch (error) {
    toast.error("Gagal mengunduh kwitansi!");
  }
};

const columns = [
  columnHelper.accessor("nis", {
    header: "NIS",
    cell: (info) => info.getValue(),
  }),
  columnHelper.accessor("photo", {
    header: "Foto",
    cell: (info) => {
      const photo = info.getValue();
      const src = photo ? `/storage/photos/${photo}` : "/media/avatars/blank.png";
      return h("img", {
        src,
        alt: "Foto Siswa",
        class: "rounded",
        style: { width: "40px", height: "40px", objectFit: "cover" },
      });
    },
  }),
  columnHelper.accessor("name", {
    header: "Nama Siswa",
    cell: (info) => h("div", { class: "fw-bold" }, info.getValue()),
  }),
  columnHelper.accessor("class_name", {
    header: "Kelas",
  }),
  columnHelper.accessor("total_debt", {
    header: "Total Tunggakan",
    cell: (info) => {
      const value = info.getValue();
      return h(
        "span",
        {
          class: value > 0 ? "text-danger fw-bold" : "text-success",
        },
        formatRupiah(value)
      );
    },
  }),
  columnHelper.display({
    id: "unpaid_months",
    header: "Bulan Belum Dibayar",
    cell: (info) => {
      const months = info.row.original.unpaid_months;
      if (!months || months.length === 0) {
        return h("span", { class: "badge badge-success" }, "Lunas");
      }
      return h(
        "span",
        { class: "badge badge-danger" },
        `${months.length} bulan`
      );
    },
  }),
  columnHelper.display({
    id: "actions",
    header: "Aksi",
    cell: (info) => {
      const student = info.row.original;
      return h("div", { class: "d-flex gap-2" }, [
        // Lihat detail
        h(
          "button",
          {
            class: "btn btn-sm btn-icon btn-info",
            title: "Lihat Detail Tunggakan",
            onClick: () => viewDetail(student),
          },
          h("i", { class: "ki-outline ki-eye fs-2" })
        ),
        // Cetak kwitansi
        student.total_debt > 0 &&
          h(
            "button",
            {
              class: "btn btn-sm btn-icon btn-warning",
              title: "Cetak Kwitansi",
              onClick: () => printInvoice(student.id),
            },
            h("i", { class: "ki-outline ki-printer fs-2" })
          ),
        // WhatsApp reminder (opsional)
        h(
          "a",
          {
            href: `https://wa.me/${student.phone.replace(/[^0-9]/g, "")}?text=Assalamualaikum,%20Orang%20tua%20Wali%20dari%20${encodeURIComponent(
              student.name
            )}.%20Mohon%20segera%20melunasi%20tunggakan%20SPP%20sebesar%20${formatRupiah(
              student.total_debt
            )}.%20Terima%20kasih.`,
            class: "btn btn-sm btn-icon btn-success",
            target: "_blank",
            title: "Kirim Reminder WA",
          },
          h("i", { class: "ki-outline ki-message-text-2 fs-2" })
        ),
      ]);
    },
  }),
];

watch(openDetail, (val) => {
  if (!val) {
    selectedStudent.value = null;
  }
});
</script>

<template>
  <!-- Modal Detail Tunggakan -->
  <div class="modal fade" id="modalDetailDebt" tabindex="-1" v-if="openDetail">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Detail Tunggakan - {{ selectedStudent?.name }}</h3>
          <button
            type="button"
            class="btn-close"
            @click="openDetail = false"
          ></button>
        </div>
        <div class="modal-body" v-if="selectedStudent">
          <div class="row">
            <div class="col-md-4 text-center">
              <img
                :src="
                  selectedStudent.photo
                    ? `/storage/photos/${selectedStudent.photo}`
                    : '/media/avatars/blank.png'
                "
                class="rounded mb-3"
                style="width: 120px; height: 120px; object-fit: cover"
                alt="Foto Siswa"
              />
              <p class="fw-bold">{{ selectedStudent.nis }}</p>
            </div>
            <div class="col-md-8">
              <table class="table table-bordered">
                <tr>
                  <th>Nama</th>
                  <td>{{ selectedStudent.name }}</td>
                </tr>
                <tr>
                  <th>Kelas</th>
                  <td>{{ selectedStudent.class_name }}</td>
                </tr>
                <tr>
                  <th>Total Tunggakan</th>
                  <td class="text-danger fw-bold">
                    {{ formatRupiah(selectedStudent.total_debt) }}
                  </td>
                </tr>
                <tr>
                  <th>Bulan Belum Dibayar</th>
                  <td>
                    <template v-if="selectedStudent.unpaid_months.length">
                      <span
                        v-for="(month, i) in selectedStudent.unpaid_months"
                        :key="i"
                        class="badge badge-danger me-1"
                      >
                        {{ month }}
                      </span>
                    </template>
                    <span v-else class="text-success">Semua lunas</span>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="openDetail = false">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Card -->
  <div class="card">
    <div class="card-header border-0 pt-6">
      <div class="card-title">
        <h2 class="fw-bold">Dashboard Guru - Monitoring Tunggakan Siswa</h2>
      </div>
      <div class="card-toolbar">
      </div>
    </div>

    <div class="card-body pt-0">
      <!-- Statistik singkat (opsional) -->
      <div class="row mb-7">
        <div class="col-xl-4">
          <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6 bg-light-primary">
            <div class="d-flex flex-column py-2">
              <div class="fs-3 fw-bold text-primary">87 Siswa</div>
              <div class="fs-6 fw-semibold text-gray-600">Total Siswa Diajar</div>
            </div>
            <div class="d-flex align-items-center">
              <i class="ki-outline ki-profile-user fs-2x text-primary"></i>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6 bg-light-danger">
            <div class="d-flex flex-column py-2">
              <div class="fs-3 fw-bold text-danger">23 Siswa</div>
              <div class="fs-6 fw-semibold text-gray-600">Memiliki Tunggakan</div>
            </div>
            <div class="d-flex align-items-center">
              <i class="ki-outline ki-wallet fs-2x text-danger"></i>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6 bg-light-warning">
            <div class="d-flex flex-column py-2">
              <div class="fs-3 fw-bold text-warning">Rp 48.750.000</div>
              <div class="fs-6 fw-semibold text-gray-600">Total Tunggakan</div>
            </div>
            <div class="d-flex align-items-center">
              <i class="ki-outline ki-dollar fs-2x text-warning"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel dengan paginate -->
      <paginate
        ref="paginateRef"
        id="table-student-debts"
        url="/api/teacher/student-debts"
        :columns="columns"
        :per-page="15"
        search-placeholder="Cari NIS atau Nama Siswa..."
      />
    </div>
  </div>
</template>

<style scoped>
/* Opsional: styling tambahan */
.badge {
  font-size: 0.8rem;
}
</style>