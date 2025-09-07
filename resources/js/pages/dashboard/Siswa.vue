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
        foto?: string; // menambahkan foto opsional
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
            status: string; // misal: 'Berhasil' atau 'Gagal'
            date: string;
        }[];
    };
}

const data = ref<StudentDashboard | null>(null);
const isLoading = ref(true);

const formatCurrency = (val: number) =>
    new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(val);
const formatDate = (dateString: string) =>
    new Date(dateString).toLocaleDateString("id-ID", { year: "numeric", month: "short", day: "numeric" });

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

onMounted(() => fetchData());
</script>

<template>
    <div v-if="isLoading" class="p-5 text-center">
        <div class="spinner-border text-primary"></div>
    </div>

    <div v-else-if="data" class="p-4">

        <!-- Profil Singkat -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Profil Anda</h3>
                <img v-if="data.profile.foto" :src="data.profile.foto" alt="Foto Siswa" class="rounded-circle"
                    style="width: 80px; height: 80px; object-fit: cover;" />
            </div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ data.profile.nama }}</p>
                <p><strong>NIS:</strong> {{ data.profile.nis }}</p>
                <p><strong>Kelas:</strong> {{ data.profile.kelas }}</p>
                <p><strong>Jurusan:</strong> {{ data.profile.jurusan }}</p>
                <p><strong>Email:</strong> {{ data.profile.email }}</p>
            </div>
        </div>

        <div class="row">

            <!-- Tagihan Saya (Belum Dibayar) -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Tagihan</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li v-for="bill in data.bills.filter(b => b.status === 'Belum Dibayar')" :key="bill.id"
                                class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div>{{ bill.title }}</div> <!-- pastikan di controller JSON ada field title -->
                                    <small class="text-muted">Jatuh tempo: {{ formatDate(bill.due_date) }}</small>
                                </div>
                                <span class="text-danger">{{ formatCurrency(bill.amount) }}</span>
                            </li>
                        </ul>

                        <div v-if="data.bills.filter(b => b.status === 'Belum Dibayar').length === 0"
                            class="text-muted text-center mt-2">
                            Tidak ada tagihan belum dibayar
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabungan Saya (Transaksi Berhasil) -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Tabungan Saya</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Saldo:</strong> {{ formatCurrency(data.savings.saldo) }}</p>
                        <h6>Transaksi Terakhir:</h6>
                        <ul class="list-group list-group-flush">
                            <li v-for="t in data.savings.transactions" :key="t.id"
                                class="list-group-item d-flex justify-content-between align-items-center">
                                <div>{{ t.type }}</div>
                                <div>{{ formatCurrency(t.amount) }}</div>
                                <small class="text-muted">{{ formatDate(t.date) }}</small>
                            </li>
                        </ul>
                        <div v-if="data.savings.transactions.length === 0" class="text-muted text-center mt-2">
                            Tidak ada transaksi
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div v-else class="text-center p-5">
        <p class="text-gray-500">Data tidak tersedia. Silakan refresh halaman.</p>
        <button class="btn btn-primary" @click="fetchData()">Refresh</button>
    </div>
</template>
