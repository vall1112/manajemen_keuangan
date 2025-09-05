<template>
  <div class="min-h-screen bg-body dark:bg-coal-500 transition-colors duration-300">
    <!-- Header -->
    <div class="header" data-kt-sticky="true" data-kt-sticky-name="header">
      <div class="container-fluid d-flex align-items-stretch justify-content-between" id="kt_header_nav">
        <div class="d-flex align-items-center">
          <div class="symbol symbol-50px me-5">
            <div class="symbol-label bg-primary">
              <i class="ki-duotone ki-wallet fs-2x text-white">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
              </i>
            </div>
          </div>
          <div>
            <h1 class="fs-2 fw-bold text-gray-900 dark:text-gray-100 mb-0">Dashboard Keuangan</h1>
            <span class="fs-7 text-muted">SMA Negeri 1 Jakarta</span>
          </div>
        </div>
        <div class="d-flex align-items-center">
          <div class="text-end">
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid mt-5">
      <!-- Student Info Card -->
      <div class="card card-flush mb-5 mb-xl-8">
        <div class="card-body pt-5">
          <div class="d-flex align-items-center">
            <div class="symbol symbol-65px me-5">
              <div class="symbol-label bg-light-primary text-primary fs-1 fw-bold">
                {{ student.name.split(' ').map(n => n[0]).join('') }}
              </div>
            </div>
            <div class="d-flex flex-column">
              <div class="fs-2 fw-bold text-gray-900 dark:text-gray-100">{{ student.name }}</div>
              <div class="fs-6 text-muted">{{ student.class }} • NISN: {{ student.nisn }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Cards Row -->
      <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <!-- Balance Card -->
        <div class="col-xl-6">
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100" 
               style="background-color: #1B84FF; background-image: url('/metronic8/media/patterns/vector-1.png')">
            <div class="card-header pt-5">
              <div class="card-title d-flex flex-column">
                <div class="d-flex align-items-center">
                  <div class="symbol symbol-45px me-3">
                    <div class="symbol-label bg-white bg-opacity-20">
                      <i class="ki-duotone ki-wallet fs-2x text-white">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                      </i>
                    </div>
                  </div>
                  <span class="fs-4 fw-semibold text-white me-1">Saldo Tabungan</span>
                </div>
              </div>
              <div class="card-toolbar">
                <button @click="setShowBalance(!showBalance)" 
                        class="btn btn-sm btn-icon btn-color-white btn-active-white btn-active-color-primary">
                  <i v-if="showBalance" class="ki-duotone ki-eye fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                  </i>
                  <i v-else class="ki-duotone ki-eye-slash fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                  </i>
                </button>
              </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
              <div class="d-flex flex-column">
                <span class="fw-bolder fs-2x text-white">
                  {{ showBalance ? formatCurrency(student.balance) : '••••••••' }}
                </span>
                <div class="d-flex align-items-center">
                  <i class="ki-duotone ki-arrow-up fs-5 text-white me-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  <span class="fs-7 fw-semibold text-white opacity-75">Tersedia untuk digunakan</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bills Summary Card -->
        <div class="col-xl-6">
          <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
              <div class="card-title d-flex flex-column">
                <div class="d-flex align-items-center">
                  <div class="symbol symbol-45px me-3">
                    <div class="symbol-label bg-light-primary">
                      <i class="ki-duotone ki-notepad-edit fs-2x text-primary">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </div>
                  </div>
                  <span class="fs-4 fw-semibold text-gray-900 dark:text-gray-100 me-1">Ringkasan Tagihan</span>
                </div>
              </div>
            </div>
            <div class="card-body d-flex flex-column pt-0">
              <div class="row g-0 mb-3">
                <div class="col bg-light-success px-6 py-8 rounded-2 me-3">
                  <span class="fs-7 text-success fw-semibold d-block mb-2">Total Tagihan</span>
                  <span class="fs-2x fw-bold text-gray-800 dark:text-gray-100">{{ formatCurrency(totalTagihanBills) }}</span>
                </div>
                <div class="col bg-light-danger px-6 py-8 rounded-2">
                  <span class="fs-7 text-danger fw-semibold d-block mb-2">Terlambat</span>
                  <span class="fs-2x fw-bold text-gray-800 dark:text-gray-100">{{ overdueBills }}</span>
                  <span class="fs-7 text-muted ms-1">Tagihan</span>
                </div>
              </div>
              <div v-if="overdueBills > 0" class="notice d-flex bg-light-danger rounded border-danger border border-dashed p-3">
                <i class="ki-duotone ki-information-5 fs-2tx text-danger me-4">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
                <div class="d-flex flex-stack flex-grow-1">
                  <div class="fw-semibold">
                    <div class="fs-6 text-gray-700 dark:text-gray-300">Segera lakukan pembayaran tagihan yang terlambat!</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bills Table -->
      <div class="card">
        <div class="card-header border-0 pt-6">
          <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <input type="text" class="form-control form-control-solid w-250px ps-13" 
                     placeholder="Cari tagihan..." v-model="searchTerm" />
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end">
              <span class="fs-4 fw-semibold text-gray-900 dark:text-gray-100">Daftar Tagihan</span>
            </div>
          </div>
        </div>
        <div class="card-body pt-0">
          <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                  <th class="min-w-125px">Jenis Tagihan</th>
                  <th class="min-w-125px">Periode</th>
                  <th class="min-w-125px">Jatuh Tempo</th>
                  <th class="min-w-70px">Jumlah</th>
                  <th class="min-w-100px">Status</th>
                  <th class="text-end min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="text-gray-600 dark:text-gray-400 fw-semibold">
                <tr v-for="bill in filteredBills" :key="bill.id" class="odd">
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="symbol symbol-45px me-5">
                        <div class="symbol-label bg-light-primary text-primary">
                          <i class="ki-duotone ki-notepad-edit fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                          </i>
                        </div>
                      </div>
                      <div class="d-flex justify-content-start flex-column">
                        <span class="text-gray-900 dark:text-gray-100 fw-bold fs-6">{{ bill.type }}</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="text-gray-800 dark:text-gray-200 fw-bold fs-6">{{ bill.month }}</span>
                  </td>
                  <td>
                    <span class="text-gray-800 dark:text-gray-200 fw-bold fs-6">{{ formatDate(bill.due_date) }}</span>
                  </td>
                  <td>
                    <span class="text-gray-900 dark:text-gray-100 fw-bold fs-6">{{ formatCurrency(bill.amount) }}</span>
                  </td>
                  <td>
                    <span :class="getStatusBadgeClass(bill.status)">{{ getStatusText(bill.status) }}</span>
                  </td>
                  <td class="text-end">
                    <button v-if="bill.status !== 'paid'" 
                            class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">
                      Bayar
                    </button>
                    <span v-else class="badge badge-light-success">Lunas</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="row g-5 g-xl-8 mt-5">
        <div class="col-xl-4">
          <button class="btn btn-primary btn-flex btn-center w-100 h-60px">
            <i class="ki-duotone ki-credit-cart fs-2 me-3">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
            Bayar Tagihan
          </button>
        </div>
        <div class="col-xl-4">
          <button class="btn btn-light-primary btn-flex btn-center w-100 h-60px">
            <i class="ki-duotone ki-time fs-2 me-3">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
            Riwayat Transaksi
          </button>
        </div>
        <div class="col-xl-4">
          <button class="btn btn-light-success btn-flex btn-center w-100 h-60px">
            <i class="ki-duotone ki-download fs-2 me-3">
              <span class="path1"></span>
            </i>
            Unduh Laporan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showBalance: true,
      currentTime: new Date(),
      searchTerm: '',
      student: {
        name: 'Ahmad Rizki Pratama',
        class: 'XII IPA 1',
        nisn: '1234567890',
        balance: 2750000,
      },
      bills: [
        {
          id: 1,
          type: 'SPP',
          month: 'September 2025',
          due_date: '2025-09-10',
          amount: 350000,
          status: 'pending'
        },
        {
          id: 2,
          type: 'Buku Paket',
          month: 'Tahun Ajaran 2025/2026',
          due_date: '2025-08-25',
          amount: 500000,
          status: 'overdue'
        },
        {
          id: 3,
          type: 'Seragam',
          month: 'Tahun Ajaran 2025/2026',
          due_date: '2025-09-15',
          amount: 275000,
          status: 'pending'
        },
        {
          id: 4,
          type: 'Praktikum Kimia',
          month: 'Semester 1 2025/2026',
          due_date: '2025-08-30',
          amount: 150000,
          status: 'paid'
        }
      ],
      searchName: '',
    };
  },
  computed: {
    totalTagihanBills() {
      return this.bills.reduce((sum, bill) => sum + bill.amount, 0);
    },
    overdueBills() {
      return this.bills.filter(bill => bill.status === 'overdue').length;
    },
    filteredBills() {
      if (!this.searchTerm) return this.bills;
      return this.bills.filter(bill => 
        bill.type.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
        bill.month.toLowerCase().includes(this.searchTerm.toLowerCase())
      );
    }
  },
  methods: {
    formatCurrency(amount) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(amount);
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      });
    },
    getStatusBadgeClass(status) {
      switch (status) {
        case 'pending': return 'badge badge-light-warning';
        case 'overdue': return 'badge badge-light-danger';
        case 'paid': return 'badge badge-light-success';
        default: return 'badge badge-light-secondary';
      }
    },
    getStatusText(status) {
      switch (status) {
        case 'pending': return 'Menunggu';
        case 'overdue': return 'Terlambat';
        case 'paid': return 'Lunas';
        default: return 'Unknown';
      }
    },
    setShowBalance(value) {
      this.showBalance = value;
    },
    // Auto-detect system theme
    detectTheme() {
      if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    }
  },
  mounted() {
    // Initialize theme detection
    this.detectTheme();
    
    // Listen for system theme changes
    if (window.matchMedia) {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', this.detectTheme);
    }
    
    // Update time every second
    this.timer = setInterval(() => {
      this.currentTime = new Date();
    }, 1000);
  },
  beforeUnmount() {
    if (this.timer) {
      clearInterval(this.timer);
    }
    
    // Clean up theme listener
    if (window.matchMedia) {
      window.matchMedia('(prefers-color-scheme: dark)').removeEventListener('change', this.detectTheme);
    }
  }
};
</script>

<style scoped>
/* Metronic-inspired custom styles */
.header {
  @apply bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50;
  transition: all 0.3s ease;
}

.card {
  @apply bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700;
  transition: all 0.3s ease;
}

.card-flush {
  @apply shadow-sm;
}

.bg-body {
  @apply bg-gray-50;
}

.bg-coal-500 {
  @apply bg-gray-900;
}

.bg-light-primary {
  @apply bg-blue-50 dark:bg-blue-900/20;
}

.bg-light-success {
  @apply bg-green-50 dark:bg-green-900/20;
}

.bg-light-danger {
  @apply bg-red-50 dark:bg-red-900/20;
}

.bg-light-warning {
  @apply bg-yellow-50 dark:bg-yellow-900/20;
}

.text-primary {
  @apply text-blue-600 dark:text-blue-400;
}

.text-success {
  @apply text-green-600 dark:text-green-400;
}

.text-danger {
  @apply text-red-600 dark:text-red-400;
}

.text-warning {
  @apply text-yellow-600 dark:text-yellow-400;
}

.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white border-blue-600;
}

.btn-light-primary {
  @apply bg-blue-50 hover:bg-blue-100 text-blue-600 border-blue-200 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800;
}

.btn-light-success {
  @apply bg-green-50 hover:bg-green-100 text-green-600 border-green-200 dark:bg-green-900/20 dark:hover:bg-green-900/30 dark:text-green-400 dark:border-green-800;
}

.badge-light-warning {
  @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300;
}

.badge-light-danger {
  @apply bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300;
}

.badge-light-success {
  @apply bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300;
}

.form-control-solid {
  @apply bg-gray-100 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200;
}

.table {
  @apply dark:text-gray-300;
}

.table thead th {
  @apply dark:text-gray-400;
}

/* Smooth transitions for all elements */
* {
  transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
}

/* Custom scrollbar for dark mode */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  @apply bg-gray-100 dark:bg-gray-800;
}

::-webkit-scrollbar-thumb {
  @apply bg-gray-400 dark:bg-gray-600 rounded-full;
}

::-webkit-scrollbar-thumb:hover {
  @apply bg-gray-500 dark:bg-gray-500;
}
</style>