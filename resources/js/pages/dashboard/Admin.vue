<script setup lang="ts">
import { ref, computed, onMounted } from "vue"
import { useRouter } from "vue-router"
import axios from "axios"
import { Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement } from "chart.js"
import { Bar } from "vue-chartjs"

// register chart components
ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement)

const router = useRouter()

interface DashboardResponse {
  statistics: {
    students: number
    users: number
    classrooms: number
    majors: number
    bills: { paid: number; unpaid: number }
    transactions: { success: number; failed: number }
    savings: { deposit: number; pull: number }
    students_per_major: { jurusan: string; jumlah: number }[]
  }
  activities: {
    new_savings: {
      id: number
      siswa: string
      kelas: string
      jenis: "Setor" | "Tarik"
      nominal: number
      tanggal: string
    }[]
  }
}

const dashboardData = ref<DashboardResponse | null>(null)
const isLoading = ref(true)

const formatNumber = (val: number) => new Intl.NumberFormat("id-ID").format(val)
const formatCurrency = (nominal: number) => new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(nominal)
const formatDate = (dateString: string) => new Date(dateString).toLocaleDateString("id-ID", { year: "numeric", month: "short", day: "numeric", hour: "2-digit", minute: "2-digit" })

const goTo = (route: string) => {
  if (router.currentRoute.value.path !== route) router.push(route)
}

// Statistik utama
const statisticCards = computed(() => {
  if (!dashboardData.value) return []
  const data = dashboardData.value.statistics
  return [
    { title: "Siswa", value: formatNumber(data.students), icon: "bi bi-people-fill", gradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)", route: "/dashboard/master/students" },
    { title: "Pengguna", value: formatNumber(data.users), icon: "bi bi-person-check-fill", gradient: "linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)", route: "/dashboard/master/users" },
    { title: "Kelas", value: formatNumber(data.classrooms), icon: "bi bi-house-door-fill", gradient: "linear-gradient(135deg, #5ee7df 0%, #b490ca 100%)", route: "/dashboard/master/classrooms" },
    { title: "Jurusan", value: formatNumber(data.majors), icon: "bi bi-mortarboard-fill", gradient: "linear-gradient(135deg, #f6d365 0%, #fda085 100%)", route: "/dashboard/master/majors" }
  ]
})

// Ringkasan Tagihan
const billCards = computed(() => {
  if (!dashboardData.value) return []
  const data = dashboardData.value.statistics.bills
  return [
    { title: "Tagihan Sudah Dibayar", value: formatNumber(data.paid), icon: "bi bi-check-circle-fill", color: "success", bgColor: "light-success", route: "/bill" },
    { title: "Tagihan Belum Dibayar", value: formatNumber(data.unpaid), icon: "bi bi-exclamation-triangle-fill", color: "danger", bgColor: "light-danger", route: "/bill" }
  ]
})

// Ringkasan Transaksi
const transactionCards = computed(() => {
  if (!dashboardData.value) return []
  const data = dashboardData.value.statistics.transactions
  return [
    { title: "Transaksi Berhasil", value: formatCurrency(data.success), icon: "bi bi-graph-up-arrow", color: "success", bgColor: "light-success", route: "/transaction" },
    { title: "Transaksi Gagal", value: formatCurrency(data.failed), icon: "bi bi-graph-down-arrow", color: "danger", bgColor: "light-danger", route: "/transaction" }
  ]
})

// Ringkasan Tabungan
const savingCards = computed(() => {
  if (!dashboardData.value) return []
  const data = dashboardData.value.statistics.savings
  return [
    { title: "Total Setoran", value: formatCurrency(data.deposit), icon: "bi bi-wallet2", color: "primary", bgColor: "light-primary", route: "/savings" },
    { title: "Total Penarikan", value: formatCurrency(data.pull), icon: "bi bi-cash-coin", color: "warning", bgColor: "light-warning", route: "/savings" }
  ]
})

// Bar chart untuk distribusi siswa per jurusan
const chartData = ref({
  labels: [] as string[],
  datasets: [{ label: "Jumlah Siswa", data: [] as number[], backgroundColor: "#4facfe" }]
})
const chartOptions = {
  responsive: true,
  plugins: { legend: { display: false }, title: { display: true, text: "Data Siswa per Jurusan" } },
  scales: { y: { beginAtZero: true } }
}

onMounted(async () => {
  try {
    const res = await axios.get("/dashboard/admin")
    dashboardData.value = res.data

    // update chart
    const studentsPerMajor = dashboardData.value.statistics.students_per_major
    chartData.value.labels = studentsPerMajor.map(m => m.jurusan)
    chartData.value.datasets[0].data = studentsPerMajor.map(m => m.jumlah)
  } catch (error) {
    console.error("Error fetching dashboard data:", error)
  } finally {
    isLoading.value = false
  }
})
</script>

<template>
    <div class="card">
      <div class="card-header align-items-center">
        <h2 class="mb-0">Dashboard Admin Keuangan</h2>
      </div>

      <!-- Skeleton Loader -->
      <div v-if="isLoading" class="p-5">
        <div class="row">
          <div v-for="i in 4" :key="i" class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-4">
            <div class="card h-100 bg-light">
              <div class="card-body placeholder-glow">
                <div class="placeholder col-6 mb-3"></div>
                <div class="placeholder col-9"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Content -->
      <div v-else-if="dashboardData" class="p-4">

        <!-- Statistik -->
        <div class="row mb-5">
          <div v-for="card in statisticCards" :key="card.title" class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-4">
            <div class="card h-100 cursor-pointer text-white stat-card" role="button" @click="goTo(card.route)" :style="{ background: card.gradient }">
              <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-60px me-4 icon-glow">
                  <div class="symbol-label d-flex align-items-center justify-content-center text-white">
                    <i :class="`${card.icon} fs-2x`"></i>
                  </div>
                </div>
                <div class="d-flex flex-column">
                  <div class="fs-2hx fw-bold lh-1">{{ card.value }}</div>
                  <div class="fw-semibold fs-6 mt-1">{{ card.title }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Ringkasan Tagihan & Transaksi -->
        <div class="row mb-5">
          <div class="col-xl-6 mb-4" v-for="cardGroup in [billCards, transactionCards]" :key="cardGroup[0].title">
            <div class="card card-flush h-xl-100">
              <div class="card-header pt-4 ps-10 border-bottom pb-2">
                <h3 class="card-title">
                  <span class="card-label fw-bold text-gray-800 fs-3">
                    {{ cardGroup === billCards ? 'Ringkasan Tagihan' : 'Ringkasan Transaksi' }}
                  </span>
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-6 mb-3" v-for="card in cardGroup" :key="card.title">
                    <div :class="`bg-${card.bgColor} rounded-2 p-6 cursor-pointer`" role="button" @click="goTo(card.route)">
                      <div class="d-flex align-items-center">
                        <i :class="`${card.icon} text-${card.color} fs-2x me-3`"></i>
                        <div>
                          <div :class="`text-${card.color} fw-semibold fs-6`">{{ card.title }}</div>
                          <div class="fw-bold fs-2hx text-gray-800">{{ card.value }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Ringkasan Tabungan -->
        <div class="row mb-5">
          <div class="col-xl-6 mb-4" v-for="card in savingCards" :key="card.title">
            <div class="card card-flush h-xl-100">
              <div class="card-body d-flex align-items-center cursor-pointer" role="button" @click="goTo(card.route)">
                <i :class="`${card.icon} text-${card.color} fs-2x me-3`"></i>
                <div>
                  <div :class="`text-${card.color} fw-semibold fs-6`">{{ card.title }}</div>
                  <div class="fw-bold fs-2hx text-gray-800">{{ card.value }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Distribusi Siswa per Jurusan (Bar Chart) -->
        <div class="row mb-5">
          <div class="col-xl-12 mb-4">
            <div class="card card-flush h-xl-100">
              <div class="card-body">
                <Bar :data="chartData" :options="chartOptions" />
              </div>
            </div>
          </div>
        </div>

        <!-- Tabungan Terbaru -->
        <div class="row mb-5">
          <div class="col-xl-12 mb-4">
            <div class="card card-flush h-xl-100">
              <div class="card-header pt-5 ps-10 border-bottom pb-3">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold text-gray-800">
                    {{ dashboardData.activities.new_savings.length }} Tabungan Terbaru
                  </span>
                </h3>
              </div>
              <div class="card-body p-4">
                <div class="table-responsive">
                  <table class="table table-striped align-middle mb-0">
                    <thead class="bg-light fw-bold text-gray-700">
                      <tr>
                        <th class="px-3 py-3 text-center">#</th>
                        <th class="px-3 py-3">Siswa</th>
                        <th class="px-3 py-3">Kelas</th>
                        <th class="px-3 py-3">Jenis</th>
                        <th class="px-3 py-3">Nominal</th>
                        <th class="px-3 py-3">Tanggal</th>
                      </tr>
                    </thead>
                    <tbody v-if="dashboardData.activities.new_savings.length > 0">
                      <tr v-for="(saving, index) in dashboardData.activities.new_savings.slice(0, 5)" :key="saving.id">
                        <td class="px-3 py-3 text-center fw-bold">{{ index + 1 }}</td>
                        <td class="px-3 py-3">{{ saving.siswa }}</td>
                        <td class="px-3 py-3">{{ saving.kelas }}</td>
                        <td class="px-3 py-3">
                          <span :class="`badge ${saving.jenis === 'Setor' ? 'badge-light-success' : 'badge-light-danger'} fw-bold px-3`">
                            {{ saving.jenis }}
                          </span>
                        </td>
                        <td class="px-3 py-3">{{ formatCurrency(saving.nominal) }}</td>
                        <td class="px-3 py-3">{{ formatDate(saving.tanggal) }}</td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr>
                        <td colspan="6" class="text-center text-gray-500 py-5">
                          <i class="bi bi-inbox fs-3x text-gray-400"></i>
                          <p class="mt-3">Tidak ada tabungan baru</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Error -->
      <div v-else class="card">
        <div class="card-body text-center">
          <i class="bi bi-exclamation-triangle text-warning fs-3x mb-5"></i>
          <h3 class="text-gray-800 mb-3">Data Tidak Tersedia</h3>
          <p class="text-gray-500">Terjadi kesalahan saat mengambil data dashboard. Silakan refresh halaman.</p>
          <button class="btn btn-primary" @click="window.location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Refresh Halaman
          </button>
        </div>
      </div>

    </div>
</template>
