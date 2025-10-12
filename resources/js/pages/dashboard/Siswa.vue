<script setup lang="ts">
import { ref, onMounted } from "vue";
import axios from "axios";

interface StudentDashboard {
    profile: {
        nama: string;
        nis: string;
        kelas: string;
        jurusan: string;
        email: string;
        foto?: string;
    };
    bills: {
        id: number;
        title: string;
        amount: number;
        due_date: string;
        status: string;
    }[];
    savings: {
        saldo: number;
        transactions: {
            id: number;
            type: string;
            amount: number;
            status: string;
            date: string;
        }[];
    };
}

const data = ref<StudentDashboard | null>(null);
const isLoading = ref(true);

const formatCurrency = (val: number) =>
    new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(val);

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return isNaN(date.getTime()) ? "-" : date.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric"
    });
};

const fetchData = async () => {
    isLoading.value = true;
    try {
        const res = await axios.get("/dashboard/siswa");
        data.value = res.data;
    } catch (error) {
        console.error("Error fetching student dashboard:", error);
    } finally {
        isLoading.value = false;
    }
};

const getTransactionColor = (type: string) =>
    type.toLowerCase().includes("setor") || type.toLowerCase().includes("deposit")
        ? "success"
        : "primary";

onMounted(fetchData);
</script>

<template>
    <!-- Loading -->
    <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="min-height: 400px;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Main Dashboard -->
    <div v-else-if="data" class="row g-7">
        <!-- Sidebar -->
        <div class="col-lg-4 col-xl-3">
            <div class="card mb-6 text-center p-5">
                <div class="symbol symbol-100px symbol-circle mb-5 mx-auto">
                    <img v-if="data.profile.foto"
                        :src="data.profile.foto ? data.profile.foto : '/media/avatars/blank.png'"
                        :alt="data.profile.nama" class="img-fluid rounded-circle" />
                    <div v-else class="symbol-label fs-2 bg-light-primary text-primary">
                        {{ data.profile.nama.charAt(0).toUpperCase() }}
                    </div>
                </div>
                <h4 class="fw-bold mb-1">{{ data.profile.nama }}</h4>
                <div class="text-muted mb-3">{{ data.profile.kelas }} - {{ data.profile.jurusan }}</div>
                <div class="badge bg-light fw-semibold text-dark p-2">{{ data.profile.nis }}</div>
            </div>

            <div class="card p-5">
                <h5 class="fw-bold mb-4">Detail Profil</h5>
                <div class="mb-3"><strong>Email:</strong>
                    <div>{{ data.profile.email }}</div>
                </div>
                <div class="mb-3"><strong>Kelas:</strong>
                    <div>{{ data.profile.kelas }}</div>
                </div>
                <div><strong>Jurusan:</strong>
                    <div>{{ data.profile.jurusan }}</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8 col-xl-9">
            <!-- Summary Cards -->
            <div class="row g-4 mb-6">
                <div class="col-md-6">
                    <div class="card p-5 border-danger border-dashed">
                        <h6 class="text-danger fw-semibold mb-2">Tagihan Tertunggak</h6>
                        <h3 class="fw-bold text-dark">
                            {{data.bills.filter(b => b.status === "Belum Dibayar").length}}
                        </h3>
                        <small class="text-danger">Item</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-5 border-success border-dashed">
                        <h6 class="text-success fw-semibold mb-2">Total Saldo</h6>
                        <h3 class="fw-bold text-dark">{{ formatCurrency(data.savings.saldo) }}</h3>
                        <small class="text-success">Tabungan Aktif</small>
                    </div>
                </div>
            </div>

            <!-- Tagihan & Tabungan -->
            <div class="row g-4">
                <!-- Tagihan -->
                <div class="col-md-6">
                    <div class="card p-5 h-100">
                        <h5 class="fw-bold mb-4">Tagihan</h5>
                        <div v-if="data.bills.length === 0" class="text-center py-10 text-muted">
                            ✅ Tidak ada tagihan
                        </div>
                        <div v-else>
                            <div v-for="bill in data.bills" :key="bill.id" class="border-bottom py-3">
                                <div class="fw-semibold">{{ bill.title }}</div>
                                <div class="text-muted small">Jatuh tempo: {{ formatDate(bill.due_date) }}</div>
                                <div class="fw-bold text-danger mt-1">{{ formatCurrency(bill.amount) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabungan -->
                <div class="col-md-6">
                    <div class="card p-5 h-100">
                        <h5 class="fw-bold mb-4">Tabungan</h5>
                        <div
                            class="d-flex justify-content-between align-items-center bg-light-success rounded p-3 mb-4">
                            <span class="fw-semibold text-success">Saldo Saat Ini</span>
                            <span class="fw-bold text-success">{{ formatCurrency(data.savings.saldo) }}</span>
                        </div>
                        <div v-if="data.savings.transactions.length === 0" class="text-center text-muted py-10">
                            💳 Belum ada transaksi
                        </div>
                        <div v-else>
                            <div v-for="t in data.savings.transactions.slice(0, 5)" :key="t.id"
                                class="border-bottom py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="fw-semibold">{{ t.type }}</div>
                                        <div class="small text-muted">{{ formatDate(t.date) }}</div>
                                    </div>
                                    <div :class="`fw-bold text-${getTransactionColor(t.type)}`">
                                        {{ formatCurrency(t.amount) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error State -->
    <div v-else class="text-center py-20">
        <h4 class="text-danger mb-3">Data tidak tersedia</h4>
        <button class="btn btn-primary" @click="fetchData">🔄 Refresh Data</button>
    </div>
</template>
