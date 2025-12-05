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
    }[];
    total_tagihan: number;
    riwayat_pembayaran: {
        id: number;
        kode: string;
        tagihan: string;
        amount: number;
        status: string;
        tanggal: string;
    }[];
    savings: {
        saldo: number;
        transactions: {
            id: number;
            type: string;
            amount: number;
            created_at: string;
        }[];
    };
}

const data = ref<StudentDashboard | null>(null);
const isLoading = ref(true);

const formatCurrency = (val: number) =>
    new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(val);

const formatDate = (dateString: string | undefined) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const fetchData = async () => {
    isLoading.value = true;
    try {
        const res = await axios.get("/dashboard/siswa");
        data.value = res.data;
    } finally {
        isLoading.value = false;
    }
};

onMounted(fetchData);
</script>

<template>
    <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="min-height: 400px;">
        <div class="spinner-border text-primary"></div>
    </div>

    <div v-else-if="data" class="row g-7">

        <!-- Notifikasi Jatuh Tempo -->
        <div v-if="data.notif_tagihan.length > 0" class="alert alert-warning">
            <strong>⚠ Tagihan Mendekati Jatuh Tempo:</strong>
            <ul class="mt-2 mb-0">
                <li v-for="d in data.notif_tagihan" :key="d.id">
                    {{ d.title }} - Jatuh tempo {{ formatDate(d.due_date) }}
                    ({{ formatCurrency(d.amount) }})
                </li>
            </ul>
        </div>

        <!-- Identitas -->
        <div class="col-12">
            <div class="card p-5 d-flex flex-row align-items-center gap-5">
                <div class="symbol symbol-100px symbol-circle">
                    <img v-if="data.profile.foto"
                        :src="data.profile.foto"
                        class="img-fluid rounded-circle" />
                    <div v-else class="symbol-label fs-2 bg-light-primary text-primary">
                        {{ data.profile.nama.charAt(0).toUpperCase() }}
                    </div>
                </div>

                <div>
                    <h3 class="fw-bold">{{ data.profile.nama }}</h3>
                    <div class="text-muted mb-1">{{ data.profile.kelas }} - {{ data.profile.jurusan }}</div>
                    <div class="badge bg-light fw-semibold text-dark p-2 mb-2">{{ data.profile.nis }}</div>
                    <div><strong>Email:</strong> {{ data.profile.email }}</div>
                </div>
            </div>
        </div>

        <!-- TAGIHAN -->
        <div class="col-md-4">
            <div class="card p-5">
                <h4 class="fw-bold mb-4">Tagihan</h4>

                <div class="alert alert-danger d-flex justify-content-between">
                    <strong>Total Tagihan:</strong>
                    <span>{{ formatCurrency(data.total_tagihan) }}</span>
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Jatuh Tempo</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="data.bills.length === 0">
                            <td colspan="3" class="text-center text-muted">Tidak ada tagihan</td>
                        </tr>
                        <tr v-for="bill in data.bills" :key="bill.id">
                            <td>{{ bill.title }}</td>
                            <td>{{ formatDate(bill.due_date) }}</td>
                            <td>{{ formatCurrency(bill.amount) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="col-md-4">
            <div class="card p-5">
                <h4 class="fw-bold mb-4">Riwayat Pembayaran</h4>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tagihan</th>
                            <th>Tanggal Bayar</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="data.riwayat_pembayaran.length === 0">
                            <td colspan="3" class="text-center text-muted">Belum ada riwayat</td>
                        </tr>
                        <tr v-for="p in data.riwayat_pembayaran" :key="p.id">
                            <td>{{ p.tagihan }}</td>
                            <td>{{ formatDate(p.tanggal) }}</td>
                            <td>{{ formatCurrency(p.amount) }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <!-- Tabungan -->
        <div class="col-md-4">
            <div class="card p-5">
                <h4 class="fw-bold mb-4">Tabungan</h4>

                <div class="alert alert-success d-flex justify-content-between">
                    <strong>Saldo Saat Ini:</strong>
                    <span>{{ formatCurrency(data.savings.saldo) }}</span>
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="data.savings.transactions.length === 0">
                            <td colspan="3" class="text-center text-muted">Belum ada transaksi</td>
                        </tr>
                        <tr v-for="t in data.savings.transactions" :key="t.id">
                            <td>{{ t.type }}</td>
                            <td>{{ formatDate(t.created_at) }}</td>
                            <td>{{ formatCurrency(t.amount) }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    <div v-else class="text-center py-20">
        <h4 class="text-danger mb-3">Data tidak tersedia</h4>
        <button class="btn btn-primary" @click="fetchData">Refresh</button>
    </div>
</template>
