<script setup lang="ts">
import { ref, h, computed, watch } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import axios from "axios";
import type { Saving } from "@/types";

interface SavingDetail {
  id: number;
  tanggal: string;
  jenis: 'setor' | 'tarik';
  jumlah: number;
  saldo_sebelum: number;
  saldo_sesudah: number;
  keterangan?: string;
  created_at: string;
}

const column = createColumnHelper<Saving>();
const paginateRef = ref<any>(null);
const selectedStudent = ref<any>(null);
const showDetail = ref(false);
const savingDetails = ref<SavingDetail[]>([]);
const loadingDetail = ref(false);
const errorDetail = ref<string | null>(null);

// Kolom untuk tabel utama
const columns = [
  column.accessor("no", {
    header: "No.",
    cell: (info) => info.row.index + 1,
  }),
  column.accessor("student", {
    header: "Nama Siswa",
    cell: (info) => {
      const student = info.row.original.student;
      const nama = student?.nama ?? "-";
      
      return h(
        "button",
        {
          class: "btn btn-link p-0 text-start fw-semibold text-primary fs-6",
          style: "text-decoration: none; border: none; background: none;",
          onClick: () => showStudentDetail(info.row.original),
        },
        [
          h("i", {
            class: "ki-duotone ki-user fs-5 text-primary me-2",
          }, [
            h("span", { class: "path1" }),
            h("span", { class: "path2" }),
          ]),
          nama
        ]
      );
    },
  }),
  column.accessor("student.classroom.nama_kelas", {
    header: "Kelas",
    cell: (info) => {
      const kelas = info.row.original.student?.classroom?.nama_kelas ?? "-";
      return h("span", { class: "badge badge-light-info fs-7" }, kelas);
    },
  }),
  column.accessor("saldo", {
    header: "Saldo Tersedia",
    cell: (cell) => {
      const value = cell.getValue();
      const formatted = `Rp ${Number(value).toLocaleString("id-ID", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })}`;
      return h("div", { class: "d-flex align-items-center" }, [
        h("i", {
          class: "ki-duotone ki-wallet fs-5 text-success me-2",
        }, [
          h("span", { class: "path1" }),
          h("span", { class: "path2" }),
          h("span", { class: "path3" }),
          h("span", { class: "path4" }),
        ]),
        h("span", { class: "fw-bold text-gray-800" }, formatted)
      ]);
    },
  }),
];

// Kolom untuk tabel detail transaksi
const detailColumns = [
  {
    header: "Tanggal",
    accessor: "tanggal",
    cell: (data: SavingDetail) => {
      return new Date(data.tanggal).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      });
    }
  },
  {
    header: "Jenis",
    accessor: "jenis",
    cell: (data: SavingDetail) => {
      const isSetor = data.jenis === 'setor';
      return {
        class: `badge badge-light-${isSetor ? 'success' : 'danger'} fs-7`,
        icon: `ki-duotone ki-${isSetor ? 'arrow-down' : 'arrow-up'} fs-6`,
        text: isSetor ? 'Setor' : 'Tarik'
      };
    }
  },
  {
    header: "Jumlah",
    accessor: "jumlah",
    cell: (data: SavingDetail) => {
      const isSetor = data.jenis === 'setor';
      const formatted = `Rp ${Number(data.jumlah).toLocaleString("id-ID")}`;
      return {
        class: `fw-bold ${isSetor ? 'text-success' : 'text-danger'}`,
        text: `${isSetor ? '+' : '-'} ${formatted}`
      };
    }
  },
  {
    header: "Saldo Akhir",
    accessor: "saldo_sesudah",
    cell: (data: SavingDetail) => {
      return `Rp ${Number(data.saldo_sesudah).toLocaleString("id-ID")}`;
    }
  }
];

// Fungsi untuk mengambil detail tabungan dari API
const fetchSavingDetails = async (studentId: number) => {
  loadingDetail.value = true;
  errorDetail.value = null;
  
  try {
    const response = await axios.get(`/students/${studentId}/savings`);
    savingDetails.value = response.data.data || [];
  } catch (error: any) {
    console.error('Error fetching saving details:', error);
    errorDetail.value = error.response?.data?.message || 'Gagal memuat detail tabungan';
    savingDetails.value = [];
  } finally {
    loadingDetail.value = false;
  }
};

// Fungsi untuk menampilkan detail siswa
const showStudentDetail = async (saving: Saving) => {
  selectedStudent.value = saving;
  showDetail.value = true;
  
  if (saving.student?.id) {
    await fetchSavingDetails(saving.student.id);
  }
};

// Fungsi untuk menutup detail
const closeDetail = () => {
  showDetail.value = false;
  selectedStudent.value = null;
  savingDetails.value = [];
  errorDetail.value = null;
};

// Computed untuk format saldo detail
const formattedSaldo = computed(() => {
  if (!selectedStudent.value?.saldo) return "Rp 0";
  return `Rp ${Number(selectedStudent.value.saldo).toLocaleString("id-ID")}`;
});

// Computed untuk statistik transaksi
const transactionStats = computed(() => {
  if (!savingDetails.value.length) return null;
  
  const totalSetor = savingDetails.value
    .filter(item => item.jenis === 'setor')
    .reduce((sum, item) => sum + item.jumlah, 0);
    
  const totalTarik = savingDetails.value
    .filter(item => item.jenis === 'tarik')
    .reduce((sum, item) => sum + item.jumlah, 0);
    
  const totalTransactions = savingDetails.value.length;
  
  return {
    totalSetor,
    totalTarik,
    totalTransactions,
    lastTransaction: savingDetails.value[0]
  };
});

const refresh = () => paginateRef.value.refetch();

// Watch untuk refresh detail ketika student berubah
watch(() => selectedStudent.value?.student?.id, (newId) => {
  if (newId && showDetail.value) {
    fetchSavingDetails(newId);
  }
});
</script>

<template>
  <div class="row g-5 g-xl-8">
    <!-- Kolom Tabel Saldo Utama -->
    <div :class="showDetail ? 'col-xl-7' : 'col-xl-12'">
      <div class="card card-flush">
        <!-- Card Header -->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
          <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-duotone ki-bank fs-1 text-primary me-3">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <div>
                <h2 class="mb-0 text-gray-900">Saldo Tabungan Siswa</h2>
                <span class="text-muted fs-7">Kelola dan pantau saldo tabungan siswa</span>
              </div>
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex align-items-center gap-2">
              <span class="badge badge-light-info fs-8">
                <i class="ki-duotone ki-information-5 fs-7 me-1">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
                Klik nama siswa untuk detail
              </span>
              <button @click="refresh" class="btn btn-sm btn-light btn-active-light-primary">
                <i class="ki-duotone ki-arrows-circle fs-5">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
                Refresh
              </button>
            </div>
          </div>
        </div>

        <!-- Card Body -->
        <div class="card-body pt-0">
          <paginate 
            ref="paginateRef" 
            id="table-savings" 
            url="/student/savings/balances" 
            :columns="columns"
            class="table-responsive"
          ></paginate>
        </div>
      </div>
    </div>

    <!-- Kolom Detail Siswa -->
    <div v-if="showDetail" class="col-xl-5">
      <div class="card card-flush">
        <!-- Detail Header -->
        <div class="card-header">
          <div class="card-title">
            <h4 class="fw-bold text-gray-900">
              <i class="ki-duotone ki-profile-circle fs-2 text-primary me-2">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
              </i>
              Detail Tabungan
            </h4>
          </div>
          <div class="card-toolbar">
            <button 
              type="button" 
              class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
              @click="closeDetail"
              data-bs-toggle="tooltip"
              title="Tutup Detail"
            >
              <i class="ki-duotone ki-cross fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </button>
          </div>
        </div>

        <!-- Detail Body -->
        <div class="card-body pt-5">
          <div v-if="selectedStudent" class="student-detail">
            <!-- Info Siswa -->
            <div class="d-flex align-items-center mb-7">
              <div class="symbol symbol-60px me-4">
                <div class="symbol-label bg-light-primary">
                  <i class="ki-duotone ki-profile-circle fs-1 text-primary">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                  </i>
                </div>
              </div>
              <div class="flex-grow-1">
                <h4 class="text-gray-900 mb-1">{{ selectedStudent.student?.nama || '-' }}</h4>
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="badge badge-light-info fs-7">
                    <i class="ki-duotone ki-abstract-39 fs-7 me-1">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                    {{ selectedStudent.student?.classroom?.nama_kelas || '-' }}
                  </span>
                  <span class="text-muted fs-8">NIS: {{ selectedStudent.student?.nis || '-' }}</span>
                </div>
              </div>
            </div>

            <!-- Saldo Utama -->
            <div class="card bg-light-success mb-6">
              <div class="card-body text-center py-6">
                <i class="ki-duotone ki-wallet fs-3tx text-success mb-3">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                  <span class="path4"></span>
                </i>
                <h6 class="text-muted mb-2 fs-7">SALDO SAAT INI</h6>
                <h2 class="text-success fw-bold mb-0">{{ formattedSaldo }}</h2>
              </div>
            </div>

            <!-- Statistik Transaksi -->
            <div v-if="transactionStats" class="row g-3 mb-6">
              <div class="col-4">
                <div class="card bg-light-success">
                  <div class="card-body text-center py-4">
                    <i class="ki-duotone ki-arrow-down fs-2x text-success mb-2">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                    <div class="fs-8 text-muted">Total Setor</div>
                    <div class="fw-bold fs-7">Rp {{ transactionStats.totalSetor.toLocaleString('id-ID') }}</div>
                  </div>
                </div>
              </div>
              
              <div class="col-4">
                <div class="card bg-light-danger">
                  <div class="card-body text-center py-4">
                    <i class="ki-duotone ki-arrow-up fs-2x text-danger mb-2">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                    <div class="fs-8 text-muted">Total Tarik</div>
                    <div class="fw-bold fs-7">Rp {{ transactionStats.totalTarik.toLocaleString('id-ID') }}</div>
                  </div>
                </div>
              </div>
              
              <div class="col-4">
                <div class="card bg-light-info">
                  <div class="card-body text-center py-4">
                    <i class="ki-duotone ki-chart-simple fs-2x text-info mb-2">
                      <span class="path1"></span>
                      <span class="path2"></span>
                      <span class="path3"></span>
                      <span class="path4"></span>
                    </i>
                    <div class="fs-8 text-muted">Transaksi</div>
                    <div class="fw-bold fs-7">{{ transactionStats.totalTransactions }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Riwayat Transaksi -->
            <div class="separator separator-dashed my-5"></div>
            
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="text-gray-900 fw-bold">
                <i class="ki-duotone ki-calendar fs-3 text-primary me-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
                Riwayat Transaksi
              </h5>
              <button 
                v-if="selectedStudent.student?.id" 
                @click="fetchSavingDetails(selectedStudent.student.id)"
                class="btn btn-sm btn-light btn-active-light-primary"
                :disabled="loadingDetail"
              >
                <span v-if="loadingDetail" class="indicator-progress">
                  <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
                <i v-else class="ki-duotone ki-arrows-circle fs-5 me-1">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
                Refresh
              </button>
            </div>

            <!-- Loading State -->
            <div v-if="loadingDetail" class="text-center py-10">
              <div class="d-flex flex-column align-items-center">
                <div class="spinner-border text-primary mb-4" role="status"></div>
                <span class="text-muted fs-6">Memuat riwayat transaksi...</span>
              </div>
            </div>

            <!-- Error State -->
            <div v-else-if="errorDetail" class="alert alert-light-danger d-flex align-items-center">
              <i class="ki-duotone ki-information-5 fs-2hx text-danger me-3">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
              </i>
              <div>
                <h5 class="mb-1">Error</h5>
                <span>{{ errorDetail }}</span>
              </div>
            </div>

            <!-- Data Transaksi -->
            <div v-else-if="savingDetails.length" class="table-responsive">
              <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                <thead>
                  <tr class="fw-bold text-muted">
                    <th v-for="col in detailColumns" :key="col.accessor" class="min-w-100px">
                      {{ col.header }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in savingDetails" :key="index">
                    <td class="text-gray-900 fw-bold">
                      {{ detailColumns[0].cell(item) }}
                    </td>
                    <td>
                      <span :class="detailColumns[1].cell(item).class">
                        <i :class="detailColumns[1].cell(item).icon + ' me-1'">
                          <span class="path1"></span>
                          <span class="path2"></span>
                        </i>
                        {{ detailColumns[1].cell(item).text }}
                      </span>
                    </td>
                    <td>
                      <span :class="detailColumns[2].cell(item).class">
                        {{ detailColumns[2].cell(item).text }}
                      </span>
                    </td>
                    <td class="text-gray-900 fw-bold">
                      {{ detailColumns[3].cell(item) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-10">
              <div class="d-flex flex-column align-items-center">
                <i class="ki-duotone ki-file-deleted fs-5x text-muted mb-4">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
                <h5 class="text-muted">Belum Ada Transaksi</h5>
                <span class="text-muted fs-7">Riwayat transaksi akan muncul di sini</span>
              </div>
            </div>

            <!-- Footer Info -->
            <div class="mt-6 p-4 bg-light rounded-3">
              <div class="d-flex align-items-center">
                <i class="ki-duotone ki-information-5 fs-2x text-info me-3">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
                <div>
                  <div class="text-gray-900 fw-bold fs-7 mb-1">Informasi</div>
                  <small class="text-muted fs-8">
                    Data terakhir diperbarui: {{ new Date().toLocaleDateString('id-ID', {
                      weekday: 'long',
                      year: 'numeric',
                      month: 'long',
                      day: 'numeric'
                    }) }}
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.student-detail {
  animation: slideInRight 0.4s ease-out;
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.btn-link:hover {
  text-decoration: underline !important;
}

.table th {
  border-bottom: 1px solid var(--kt-border-color);
  font-weight: 600;
  color: var(--kt-text-muted);
}

.card-flush {
  box-shadow: 0 0 50px 0 rgba(82, 63, 105, 0.15);
  border: 0;
}

.symbol-label {
  background: rgba(var(--kt-primary-rgb), 0.1);
}

.indicator-progress {
  display: block;
}

.spinner-border-sm {
  width: 1rem;
  height: 1rem;
}

/* Responsive adjustments */
@media (max-width: 1399.98px) {
  .col-xl-7 {
    order: 1;
  }
  
  .col-xl-5 {
    order: 2;
    margin-top: 1.5rem;
  }
}

@media (max-width: 767.98px) {
  .card-header {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 1rem;
  }
  
  .card-toolbar {
    width: 100%;
    justify-content: flex-end;
  }
  
  .student-detail .row.g-3 {
    margin: 0 -0.5rem;
  }
  
  .student-detail .row.g-3 > div {
    padding: 0 0.5rem;
  }
}
</style>