<script setup lang="ts">
import { ref, computed, onMounted } from "vue"
import { useRouter } from "vue-router"
import axios from "axios"
import { Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement } from "chart.js"
import { Bar } from "vue-chartjs"

ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement)

const router = useRouter()

interface DashboardResponse {
    statistics: {
        bills: { today: number; month: number }
        transactions: { total: number }
        savings: { deposit: number; pull: number; total: number }
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

// Ringkasan Keuangan
const financeCards = computed(() => {
    if (!dashboardData.value) return []
    const data = dashboardData.value.statistics
    return [
        { title: "Tagihan Hari Ini", value: formatCurrency(data.bills.today), icon: "bi bi-calendar-day", color: "primary", route: "/bill" },
        { title: "Tagihan Bulan Ini", value: formatCurrency(data.bills.month), icon: "bi bi-calendar-month", color: "info", route: "/bill" },
        { title: "Total Pembayaran", value: formatCurrency(data.transactions.total), icon: "bi bi-wallet2", color: "success", route: "/transaction" },
        { title: "Total Tabungan", value: formatCurrency(data.savings.total), icon: "bi bi-piggy-bank", color: "warning", route: "/savings" }
    ]
})

// Chart Pemasukan Bulanan
const chartData = computed(() => {
    if (!dashboardData.value) return { labels: [], datasets: [] }
    const monthly = dashboardData.value.statistics.monthly_income
    const labels = Object.keys(monthly).map(m => `Bulan ${m}`)
    const data = Object.values(monthly)
    return {
        labels,
        datasets: [
            {
                label: "Total Pemasukan",
                data,
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
    <div class="container-fluid p-5">
        <div class="card shadow-sm">
            <div class="card-header align-items-center bg-light">
                <h2 class="mb-0">Dashboard Bendahara</h2>
            </div>

            <!-- Loader -->
            <div v-if="isLoading" class="p-5 text-center">
                <div class="spinner-border text-primary"></div>
            </div>

            <!-- Content -->
            <div v-else-if="dashboardData" class="p-4">

                <!-- Ringkasan Keuangan -->
                <div class="row mb-5">
                    <div v-for="card in financeCards" :key="card.title" class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100 shadow-sm cursor-pointer" role="button" @click="goTo(card.route)">
                            <div class="card-body p-4 d-flex align-items-center">
                                <i :class="`${card.icon} text-${card.color} fs-2x me-4`"></i>
                                <div>
                                    <div class="fw-bold fs-2hx text-gray-800">{{ card.value }}</div>
                                    <div :class="`text-${card.color} fw-semibold fs-6`">{{ card.title }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Tagihan -->
                <div class="card mb-5">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">Status Tagihan</h3>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Belum Dibayar</span> <span class="fw-bold">{{
                                    dashboardData.statistics.payment_status.unpaid }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Lunas</span> <span class="fw-bold">{{ dashboardData.statistics.payment_status.paid
                                    }}</span>
                            </li>
                        </ul>
                        <div>
                            <strong>Siswa dengan tunggakan:</strong>
                            {{ dashboardData.statistics.students_unpaid }}
                        </div>
                    </div>
                </div>

                <!-- Grafik Pemasukan Per Bulan -->
                <div class="card mb-5 shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">Pemasukan Bulanan</h3>
                    </div>
                    <div class="card-body p-4">
                        <Bar :data="chartData" :options="chartOptions" />
                    </div>
                </div>

                <!-- Transaksi Terbaru -->
                <div class="card mb-5 shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">Transaksi Terbaru</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="bg-light fw-semibold text-gray-700">
                                    <tr>
                                        <th>#</th>
                                        <th>Kode</th>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        <th>Nominal</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(trx, i) in dashboardData.activities.transactions.slice(0, 5)"
                                        :key="trx.kode">
                                        <td>{{ i + 1 }}</td>
                                        <td>{{ trx.kode }}</td>
                                        <td>{{ trx.siswa }}</td>
                                        <td>{{ trx.kelas }}</td>
                                        <td>{{ formatCurrency(trx.nominal) }}</td>
                                        <td>{{ formatDate(trx.tanggal) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tabungan Terbaru -->
                <div class="card mb-5 shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">Tabungan Terbaru</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="bg-light fw-semibold text-gray-700">
                                    <tr>
                                        <th>#</th>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        <th>Jenis</th>
                                        <th>Nominal</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(saving, i) in dashboardData.activities.savings.slice(0, 5)"
                                        :key="saving.id">
                                        <td>{{ i + 1 }}</td>
                                        <td>{{ saving.siswa }}</td>
                                        <td>{{ saving.kelas }}</td>
                                        <td>
                                            <span
                                                :class="`badge ${saving.jenis === 'Setor' ? 'bg-success' : 'bg-danger'}`">
                                                {{ saving.jenis }}
                                            </span>
                                        </td>
                                        <td>{{ formatCurrency(saving.nominal) }}</td>
                                        <td>{{ formatDate(saving.tanggal) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Laporan Keuangan -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">Laporan Keuangan</h3>
                    </div>
                    <div class="card-body p-4">
                        <button class="btn btn-primary me-3">
                            <i class="bi bi-file-earmark-arrow-down"></i> Export Laporan Bulanan
                        </button>
                        <button class="btn btn-success">
                            <i class="bi bi-printer"></i> Cetak Laporan Tahunan
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
