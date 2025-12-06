<script setup lang="ts">
import { h, ref, watch, computed } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { toast } from "vue3-toastify";

// Interface untuk data siswa
interface Student {
  no?: number;
  id: number;
  uuid: string;
  // nis: string;
  name: string;
  class: string;
  photo?: string;
  total_tunggakan: number;
  bulan_tertunggak: string[];
  status_pembayaran: 'lunas' | 'belum_lunas';
}

const column = createColumnHelper<Student>();
const paginateRef = ref<any>(null);
const selectedStudent = ref<string>("");
const showDetail = ref<boolean>(false);

// Format currency
const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount);
};

// Lihat detail tunggakan
const viewDetail = (studentUuid: string) => {
  selectedStudent.value = studentUuid;
  showDetail.value = true;
};

// Export ke Excel (placeholder)
const exportToExcel = async () => {
  try {
    toast.info("Sedang mengunduh data...");
    // Implementasi export Excel
    const response = await fetch("/api/teacher/students/export");
    // Handle download
    toast.success("Data berhasil diunduh!");
  } catch (error) {
    console.error("Gagal export data:", error);
    toast.error("Gagal mengunduh data!");
  }
};

// Print tunggakan siswa
const printTunggakan = async (studentId: number) => {
  try {
    const url = `/api/teacher/students/${studentId}/print-tunggakan`;
    const response = await fetch(url);
    const printHtml = await response.text();

    const printWindow = window.open("", "PRINT", "width=900,height=650");
    if (printWindow) {
      printWindow.document.write(printHtml);
      printWindow.document.close();
      printWindow.focus();
      printWindow.print();
      printWindow.onafterprint = () => printWindow.close();
    }
  } catch (error) {
    console.error("Gagal mencetak:", error);
    toast.error("Gagal mencetak data tunggakan!");
  }
};

const columns = [
  column.accessor("no", {
    header: "#",
  }),
  column.accessor("nama", {
    header: "Nama Siswa",
  }),
  column.accessor("classroom_id", {
    header: "Kelas",
  }),
  column.accessor("classroom_id", {
    header: "Jenis Tunggakan",
  }),
  column.accessor("total_tunggakan", {
    header: "Total Tunggakan",
    cell: (cell) => {
      const amount = cell.getValue();
      const isLunas = amount === 0;
      return h("div", { class: "text-end" }, [
        h("span", {
          class: isLunas ? "badge badge-success" : "badge badge-danger",
          style: { fontSize: "12px" }
        }, formatCurrency(amount))
      ]);
    },
  }),
  column.accessor("status_pembayaran", {
    header: "Status",
    cell: (cell) => {
      const status = cell.getValue();
      const isLunas = status === 'lunas';
      return h("div", { class: "text-center" }, [
        h("span", {
          class: isLunas
            ? "badge badge-light-success"
            : "badge badge-light-danger",
        }, isLunas ? "Lunas" : "Belum Lunas")
      ]);
    },
  }),
  column.accessor("uuid", {
    header: "Aksi",
    cell: (info) => {
      const student = info.row.original;
      return h("div", { class: "d-flex gap-2 justify-content-center" }, [
        // Tombol Detail
        h(
          "button",
          {
            class: "btn btn-sm btn-icon btn-primary",
            onClick: () => viewDetail(student.uuid),
            title: "Lihat Detail Tunggakan",
          },
          h("i", { class: "la la-eye fs-2" })
        ),
      ]);
    },
  }),
];
watch(showDetail, (val) => {
  if (!val) selectedStudent.value = "";
  window.scrollTo(0, 0);
});
</script>

<template>
  <!-- Modal Detail (Component terpisah) -->
  <!-- <DetailTunggakan :student-uuid="selectedStudent" @close="showDetail = false" v-if="showDetail" /> -->

  <!-- Card Statistik -->
  <div class="row g-5 g-xl-8 mb-5">
    <div class="col-xl-3">
      <div class="card card-xl-stretch">
        <div class="card-body">
          <i class="la la-users text-primary fs-2x"></i>
          <div class="text-gray-900 fw-bold fs-2 mb-2 mt-5">150</div>
          <div class="fw-semibold text-gray-400">Total Siswa</div>
        </div>
      </div>
    </div>
    <div class="col-xl-3">
      <div class="card card-xl-stretch">
        <div class="card-body">
          <i class="la la-check-circle text-success fs-2x"></i>
          <div class="text-gray-900 fw-bold fs-2 mb-2 mt-5">120</div>
          <div class="fw-semibold text-gray-400">Siswa Lunas</div>
        </div>
      </div>
    </div>
    <div class="col-xl-3">
      <div class="card card-xl-stretch">
        <div class="card-body">
          <i class="la la-exclamation-circle text-danger fs-2x"></i>
          <div class="text-gray-900 fw-bold fs-2 mb-2 mt-5">30</div>
          <div class="fw-semibold text-gray-400">Siswa Menunggak</div>
        </div>
      </div>
    </div>
    <div class="col-xl-3">
      <div class="card card-xl-stretch">
        <div class="card-body">
          <i class="la la-money-bill text-warning fs-2x"></i>
          <div class="text-gray-900 fw-bold fs-2 mb-2 mt-5">Rp 15.500.000</div>
          <div class="fw-semibold text-gray-400">Total Tunggakan</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Data Siswa -->
  <div class="card">
    <div class="card-header align-items-center">
      <h2 class="mb-0">Daftar Siswa & Tunggakan Pembayaran</h2>
    </div>
    <div class="card-body">
      <!-- Filter Section -->
      <div class="row mb-5">
        <div class="col-md-3">
          <label class="form-label">Filter Kelas</label>
          <select class="form-select form-select-sm">
            <option value="">Semua Kelas</option>
            <option value="7">Kelas 7</option>
            <option value="8">Kelas 8</option>
            <option value="9">Kelas 9</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Filter Status</label>
          <select class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="lunas">Lunas</option>
            <option value="belum_lunas">Belum Lunas</option>
          </select>
        </div>
      </div>

      <!-- Paginate Table -->
      <paginate ref="paginateRef" id="table-students-tunggakan" url="/master/students" :columns="columns"></paginate>
    </div>
  </div>
</template>

<style scoped>
.card-xl-stretch {
  height: 100%;
}

.card-body {
  padding: 1.5rem;
}

.badge {
  padding: 0.5rem 0.75rem;
}
</style>