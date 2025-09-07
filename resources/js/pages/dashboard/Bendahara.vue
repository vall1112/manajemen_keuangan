<script setup lang="ts">
import { ref, computed, onMounted } from "vue"
import { useRouter } from "vue-router"
import axios from "axios"
import {
    Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement, ArcElement
} from "chart.js"
import { Bar, Pie } from "vue-chartjs"

ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement, ArcElement)

const router = useRouter()

// API Response Interface
interface DashboardResponse {
    statistics: {
        bills: { today: number; month: number; total: number }
        transactions: { total: number; today: number; month: number }
        savings: { deposit: number; pull: number; balance: number }
        payment_status: { unpaid: number; paid: number }
        payment_types: { jenis: string; jumlah: number }[]
        monthly_income: Record<string, number>
        students_unpaid: number
    }
    activities: {
        transactions: {
            kode: string
            siswa: string
            kelas: string
            nominal: number
            tanggal: string
        }[]
        savings: {
            id: number
            siswa: string
            kelas: string
            jenis: string
            nominal: number
            tanggal: string
        }[]
    }
}

const dashboardData = ref<DashboardResponse | null>(null)
const isLoading = ref(true)

// --- Pagination Config ---
const itemsPerPage = 5

// ==========================
// FILTER TAHUN (3 tahun terakhir)
// ==========================
const currentYear = new Date().getFullYear()
const last3Years = [currentYear, currentYear - 1, currentYear - 2]

// transaksi
const transaksiYear = ref(currentYear)
const transaksiYears = computed(() => last3Years)

const transaksiPage = ref(1)
const filteredTransactions = computed(() => {
    if (!dashboardData.value) return []
    return dashboardData.value.activities.transactions.filter(
        trx => new Date(trx.tanggal).getFullYear() === transaksiYear.value
    )
})
const paginatedTransactions = computed(() => {
    const start = (transaksiPage.value - 1) * itemsPerPage
    return filteredTransactions.value.slice(start, start + itemsPerPage)
})
const totalTransaksiPages = computed(() =>
    Math.ceil(filteredTransactions.value.length / itemsPerPage) || 1
)

// tabungan
const tabunganYear = ref(currentYear)
const tabunganYears = computed(() => last3Years)

const tabunganPage = ref(1)
const filteredSavings = computed(() => {
    if (!dashboardData.value) return []
    return dashboardData.value.activities.savings.filter(
        s => new Date(s.tanggal).getFullYear() === tabunganYear.value
    )
})
const paginatedSavings = computed(() => {
    const start = (tabunganPage.value - 1) * itemsPerPage
    return filteredSavings.value.slice(start, start + itemsPerPage)
})
const totalTabunganPages = computed(() =>
    Math.ceil(filteredSavings.value.length / itemsPerPage) || 1
)

// ringkasan tabungan
const savingsYear = ref(currentYear)
const savingsYears = computed(() => last3Years)

// transaksi bulanan
const chartYear = ref(currentYear)
const chartYears = computed(() => last3Years)

// ==========================
// FORMATTERS
// ==========================
const formatCurrency = (val: number) =>
    new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(val)

const formatDate = (dateString: string) =>
    new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit"
    })

const goTo = (route: string) => {
    if (router.currentRoute.value.path !== route) router.push(route)
}

// ==========================
// CARD RINGKASAN
// ==========================
const financeCards = computed(() => {
    if (!dashboardData.value) return []
    const data = dashboardData.value.statistics
    return [
        {
            title: "Saldo Kas",
            value: formatCurrency(data.saldo_kas),
            icon: "bi bi-wallet2",   // ikon dompet
            color: "primary",
            route: "/transaction"
        },
        {
            title: "Pemasukan Hari Ini",
            value: formatCurrency(data.pemasukan_hari_ini),
            icon: "bi bi-cash-coin", // ikon uang masuk
            color: "success",
            route: "/transaction"
        },
        {
            title: "Tagihan Belum Dibayar",
            value: formatCurrency(data.tagihan_belum_bayar),
            icon: "bi bi-receipt-cutoff", // ikon tagihan
            color: "danger",
            route: "/bill"
        },
        {
            title: "Saldo Tabungan",
            value: formatCurrency(data.saldo_tabungan),
            icon: "bi bi-piggy-bank-fill", // ikon tabungan
            color: "warning",
            route: "/savings/balance"
        }
    ]
})


// ==========================
// GRAFIK
// ==========================
const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"]

// transaksi bulanan
const chartData = computed(() => {
    if (!dashboardData.value) return { labels: [], datasets: [] }

    const trxThisYear = dashboardData.value.activities.transactions.filter(
        trx => new Date(trx.tanggal).getFullYear() === chartYear.value
    )

    const monthly = Array(12).fill(0)
    trxThisYear.forEach(trx => {
        const month = new Date(trx.tanggal).getMonth()
        monthly[month] += trx.nominal
    })

    return {
        labels: monthNames,
        datasets: [
            {
                label: `Pemasukan ${chartYear.value}`,
                data: monthly,
                backgroundColor: "#4facfe"
            }
        ]
    }
})

const chartOptions = {
    responsive: true,
    plugins: { legend: { display: false }, title: { display: true, text: "Pemasukan Per Bulan" } },
    scales: { y: { beginAtZero: true } }
}

// jenis pembayaran
const randomColors = (count: number) =>
    Array.from({ length: count }, () =>
        `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`
    )

const paymentTypeData = computed(() => {
    if (!dashboardData.value) return { labels: [], datasets: [] }
    const types = dashboardData.value.statistics.payment_types
    return {
        labels: types.map(t => t.jenis),
        datasets: [
            {
                data: types.map(t => t.jumlah),
                backgroundColor: randomColors(types.length)
            }
        ]
    }
})

const paymentTypeOptions = {
    responsive: true,
    plugins: {
        legend: { position: "bottom" },
        title: { display: true, text: "Jenis Pembayaran" }
    }
}

// ringkasan tabungan
const savingsSummaryData = computed(() => {
    if (!dashboardData.value) return { labels: [], datasets: [] }
    const savingsThisYear = dashboardData.value.activities.savings.filter(
        s => new Date(s.tanggal).getFullYear() === savingsYear.value
    )
    const deposit = savingsThisYear.filter(s => s.jenis === "Setor").reduce((a, b) => a + b.nominal, 0)
    const pull = savingsThisYear.filter(s => s.jenis === "Tarik").reduce((a, b) => a + b.nominal, 0)

    return {
        labels: ["Setor", "Tarik"],
        datasets: [
            {
                data: [deposit, pull],
                backgroundColor: ["#28a745", "#dc3545"]
            }
        ]
    }
})

const savingsSummaryOptions = {
    responsive: true,
    plugins: {
        legend: { position: "bottom" },
        title: { display: true, text: "Ringkasan Tabungan" }
    }
}

onMounted(async () => {
    try {
        const res = await axios.get("/dashboard/bendahara")
        dashboardData.value = res.data
    } catch (error) {
        console.error("Error fetching dashboard data:", error)
    } finally {
        isLoading.value = false
    }
})
</script>

<template>
    <!-- Loader -->
    <div v-if="isLoading" class="p-5 text-center">
        <div class="spinner-border text-primary"></div>
    </div>

    <!-- Content -->
    <div v-else-if="dashboardData" class="p-4">

        <!-- Ringkasan Keuangan (Atas) -->
        <div class="row mb-5">
            <div v-for="card in financeCards" :key="card.title" class="col-md-6 col-xl-3 mb-4">
                <div class="card h-100 shadow-sm text-white cursor-pointer" :class="`bg-${card.color}`" role="button"
                    @click="goTo(card.route)">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Kiri: Value & Title -->
                            <div>
                                <div class="fw-bold fs-2hx">{{ card.value }}</div>
                                <div class="fw-semibold fs-6">{{ card.title }}</div>
                            </div>

                            <!-- Kanan: Icon -->
                            <div
                                class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center p-3">
                                <i :class="`${card.icon} fs-2x text-white`"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Bawah: Tabel + Grafik -->
        <div class="row mb-5">

            <!-- Kiri: Tabel -->
            <div class="col-lg-8">
                <!-- Transaksi Terbaru -->
                <div class="card card-custom gutter-b mb-5">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            <h3 class="card-label">Rincian Transaksi</h3>
                        </div>
                        <select v-model="transaksiYear" class="form-select w-auto">
                            <option v-for="y in transaksiYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Nominal</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody v-if="filteredTransactions.length">
                                <tr v-for="(trx, i) in paginatedTransactions" :key="trx.kode">
                                    <td>{{ (transaksiPage - 1) * itemsPerPage + i + 1 }}</td>
                                    <td>{{ trx.kode }}</td>
                                    <td>{{ trx.siswa }}</td>
                                    <td>{{ trx.kelas }}</td>
                                    <td>{{ formatCurrency(trx.nominal) }}</td>
                                    <td>{{ formatDate(trx.tanggal) }}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak menemukan data</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Info + Pagination -->
                        <div v-if="filteredTransactions.length"
                            class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Showing
                                {{ (transaksiPage - 1) * itemsPerPage + 1 }}
                                to
                                {{ Math.min(transaksiPage * itemsPerPage, filteredTransactions.length) }}
                                of
                                {{ filteredTransactions.length }}
                                entries
                            </div>
                            <nav>
                                <ul class="pagination mb-0">
                                    <li class="page-item" :class="{ disabled: transaksiPage === 1 }">
                                        <button class="page-link" @click="transaksiPage--"
                                            :disabled="transaksiPage === 1">Previous</button>
                                    </li>
                                    <li class="page-item" v-for="page in totalTransaksiPages" :key="page"
                                        :class="{ active: transaksiPage === page }">
                                        <button class="page-link" @click="transaksiPage = page">{{ page
                                            }}</button>
                                    </li>
                                    <li class="page-item" :class="{ disabled: transaksiPage === totalTransaksiPages }">
                                        <button class="page-link" @click="transaksiPage++"
                                            :disabled="transaksiPage === totalTransaksiPages">Next</button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Tabungan Terbaru -->
                <div class="card card-custom gutter-b">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            <h3 class="card-label">Rincian Tabungan</h3>
                        </div>
                        <select v-model="tabunganYear" class="form-select w-auto">
                            <option v-for="y in tabunganYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Jenis</th>
                                    <th>Nominal</th>
                                    <th>Tanggal dan Waktu</th>
                                </tr>
                            </thead>
                            <tbody v-if="filteredSavings.length">
                                <tr v-for="(saving, i) in paginatedSavings" :key="saving.id">
                                    <td>{{ (tabunganPage - 1) * itemsPerPage + i + 1 }}</td>
                                    <td>{{ saving.siswa }}</td>
                                    <td>{{ saving.kelas }}</td>
                                    <td>
                                        <span :class="`badge ${saving.jenis === 'Setor' ? 'bg-success' : 'bg-danger'}`">
                                            {{ saving.jenis }}
                                        </span>
                                    </td>
                                    <td>{{ formatCurrency(saving.nominal) }}</td>
                                    <td>{{ formatDate(saving.tanggal) }}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak menemukan data</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Info + Pagination -->
                        <div v-if="filteredSavings.length"
                            class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Showing
                                {{ (tabunganPage - 1) * itemsPerPage + 1 }}
                                to
                                {{ Math.min(tabunganPage * itemsPerPage, filteredSavings.length) }}
                                of
                                {{ filteredSavings.length }}
                                entries
                            </div>
                            <nav>
                                <ul class="pagination mb-0">
                                    <li class="page-item" :class="{ disabled: tabunganPage === 1 }">
                                        <button class="page-link" @click="tabunganPage--"
                                            :disabled="tabunganPage === 1">Previous</button>
                                    </li>
                                    <li class="page-item" v-for="page in totalTabunganPages" :key="page"
                                        :class="{ active: tabunganPage === page }">
                                        <button class="page-link" @click="tabunganPage = page">{{ page
                                            }}</button>
                                    </li>
                                    <li class="page-item" :class="{ disabled: tabunganPage === totalTabunganPages }">
                                        <button class="page-link" @click="tabunganPage++"
                                            :disabled="tabunganPage === totalTabunganPages">Next</button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanan: Grafik -->
            <div class="col-lg-4">
                <!-- Jenis Pembayaran -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Jenis Pembayaran</h3>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div v-if="paymentTypeData.datasets.length && paymentTypeData.datasets[0].data.some(v => v > 0)"
                            style="max-height:250px;">
                            <Pie :data="paymentTypeData" :options="paymentTypeOptions" />
                        </div>
                        <div v-else class="text-muted">Tidak menemukan data</div>
                    </div>
                </div>

                <!-- Ringkasan Tabungan -->
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            <h3 class="card-label">Jenis Tabungan</h3>
                        </div>
                        <select v-model="savingsYear" class="form-select w-auto">
                            <option v-for="y in savingsYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div v-if="savingsSummaryData.datasets.length && savingsSummaryData.datasets[0].data.some(v => v > 0)"
                            style="max-height:250px;">
                            <Pie :data="savingsSummaryData" :options="savingsSummaryOptions" />
                        </div>
                        <div v-else class="text-muted">Tidak menemukan data</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Pemasukan Per Bulan -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom gutter-b">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            <h3 class="card-label">Transaksi Bulanan</h3>
                        </div>
                        <select v-model="chartYear" class="form-select w-auto">
                            <option v-for="y in chartYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <div v-if="chartData.datasets.length && chartData.datasets[0].data.some(v => v > 0)">
                            <Bar :data="chartData" :options="chartOptions" />
                        </div>
                        <div v-if="!chartData.datasets[0].data.some(v => v > 0)" class="text-center text-muted">
                            Tidak ada data untuk tahun ini
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
