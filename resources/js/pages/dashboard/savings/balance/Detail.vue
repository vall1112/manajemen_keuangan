<script setup lang="ts">
import { useRoute } from "vue-router";
import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

const route = useRoute();
const studentId = route.params.id as string;

// ambil detail siswa + tabungan
const { data, isLoading } = useQuery({
    queryKey: ["studentSavingsDetail", studentId],
    queryFn: async () => {
        const res = await axios.get(`/students/${studentId}/savings`);
        return res.data; // { student: {...}, savings: [...] }
    },
    enabled: !!studentId,
});

// Format currency helper
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};

// Format date helper
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Format tanggal
const formatDateOnly = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};

// Format jam
const formatTimeOnly = (dateString: string) => {
    return new Date(dateString).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Get status badge class
const getStatusBadge = (status: string) => {
    return status === 'Aktif'
        ? 'badge badge-light-success fw-bold'
        : 'badge badge-light-secondary fw-bold';
};

// Get transaction type class
const getTransactionTypeClass = (jenis: string) => {
    return jenis === 'Setor'
        ? 'text-success fw-bold'
        : 'text-danger fw-bold';
};
</script>

<template>
    <!-- Page Header -->
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <div class="card mb-5 mb-xl-8">
                <div class="card-body">
                    <div class="d-flex flex-center flex-column py-5">
                        <!-- Avatar -->
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            <div class="symbol-label fs-3 bg-light-primary text-primary overflow-hidden">
                                <img v-if="data.student.foto" :src="data.student.foto" alt="Foto Siswa"
                                    class="w-100 h-100 object-fit-cover" />
                                <i v-else class="ki-duotone ki-user fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>

                        <!-- Student Info -->
                        <div v-if="!isLoading && data" class="text-center">
                            <h3 class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">
                                {{ data.student.nama }}
                            </h3>
                            <div class="mb-9">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="ki-duotone ki-profile-circle fs-4 me-2 text-muted">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <span class="fw-semibold text-muted">{{ data.student.nis }}</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="ki-duotone ki-bank fs-4 me-2 text-muted">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span class="fw-semibold text-muted">
                                        {{ data.student.classroom?.nama_kelas ?? "-" }}
                                    </span>
                                </div>

                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="ki-duotone ki-graduatecap fs-4 me-2 text-muted">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span class="fw-semibold text-muted">
                                        {{ data.student.classroom?.major?.nama_jurusan ?? "-" }}
                                    </span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <span :class="getStatusBadge(data.student.status)">
                                {{ data.student.status }}
                            </span>
                        </div>

                        <!-- Loading State -->
                        <div v-else class="text-center">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-lg-row-fluid ms-lg-15">
            <!-- Balance Card -->
            <div class="card mb-5 mb-xl-10">
                <!-- Header -->
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">
                            <i class="ki-duotone ki-wallet fs-2 text-primary me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            Saldo Terakhir
                        </h3>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body pt-0">
                    <!-- Konten saldo -->
                    <div v-if="!isLoading && data" class="d-flex align-items-center">
                        <!-- Tambah margin-top untuk jarak dengan garis header -->
                        <div class="symbol symbol-50px me-5 mt-7">
                            <div class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-dollar fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>

                        <div class="flex-grow-1 mt-6">
                            <div class="fs-1 fw-bold text-gray-800">
                                {{ formatCurrency(Number(data.last_balance ?? 0)) }}
                            </div>
                            <div class="fs-7 text-muted">
                                Total saldo saat ini
                            </div>
                        </div>
                    </div>

                    <!-- Loading state -->
                    <div v-else class="d-flex align-items-center justify-content-center py-10">
                        <div class="spinner-border spinner-border-lg text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">
                            <i class="ki-duotone ki-chart-simple fs-2 text-primary me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            Riwayat Tabungan
                        </h3>
                    </div>
                </div>

                <div class="card-body py-4">
                    <!-- Data Table -->
                    <div v-if="!isLoading && data && data.savings?.length > 0" class="table-responsive">
                        <table class="table table-rounded table-striped border gy-7 gs-7">
                            <!-- Header -->
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                    <th class="min-w-200px">
                                        <i class="ki-duotone ki-calendar fs-6 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Tanggal
                                    </th>
                                    <th class="min-w-100px">
                                        <i class="ki-duotone ki-category fs-6 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        Jenis
                                    </th>
                                    <th class="min-w-125px">
                                        <i class="ki-duotone ki-dollar fs-6 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        Nominal
                                    </th>
                                    <th class="min-w-125px">
                                        <i class="ki-duotone ki-wallet fs-6 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        Saldo
                                    </th>
                                    <th class="min-w-200px">
                                        <i class="ki-duotone ki-notepad fs-6 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>

                            <!-- Body -->
                            <tbody>
                                <tr v-for="(item, index) in data.savings" :key="item.id">
                                    <!-- Tanggal -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40px me-3">
                                                <div class="symbol-label bg-light-info">
                                                    <i class="ki-duotone ki-time fs-6 text-info">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-semibold">
                                                    {{ formatDateOnly(item.created_at) }}
                                                </span>
                                                <span class="text-muted fs-7">
                                                    {{ formatTimeOnly(item.created_at) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Jenis -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-30px me-2">
                                                <div
                                                    :class="item.jenis === 'Setor' ? 'symbol-label bg-light-success' : 'symbol-label bg-light-danger'">
                                                    <i
                                                        :class="item.jenis === 'Setor' ? 'ki-duotone ki-arrow-up fs-7 text-success' : 'ki-duotone ki-arrow-down fs-7 text-danger'">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <span :class="getTransactionTypeClass(item.jenis)">
                                                {{ item.jenis }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Nominal -->
                                    <td>
                                        <span
                                            :class="item.jenis === 'Setor' ? 'fw-bold text-success' : 'fw-bold text-danger'">
                                            {{ item.jenis === 'Setor' ? '+' : '-' }} {{
                                                formatCurrency(Number(item.nominal)) }}
                                        </span>
                                    </td>

                                    <!-- Saldo -->
                                    <td>
                                        <span class="fw-semibold text-gray-800">
                                            {{ formatCurrency(Number(item.saldo)) }}
                                        </span>
                                    </td>

                                    <!-- Keterangan -->
                                    <td>
                                        <span class="text-gray-600">
                                            {{ item.keterangan || '-' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Empty State -->
                    <div v-else-if="!isLoading && data && (!data.savings || data.savings.length === 0)"
                        class="d-flex flex-column flex-center py-20">
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            <div class="symbol-label bg-light-warning text-warning">
                                <i class="ki-duotone ki-bank fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <h4 class="fw-semibold text-gray-800 mb-4">Belum Ada Transaksi</h4>
                        <p class="text-gray-600 fs-6 text-center mb-0">
                            Siswa ini belum memiliki riwayat transaksi tabungan
                        </p>
                    </div>

                    <!-- Loading State -->
                    <div v-else-if="isLoading" class="d-flex align-items-center justify-content-center py-20">
                        <div class="d-flex flex-column align-items-center">
                            <div class="spinner-border spinner-border-lg text-primary mb-4" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="text-muted">Memuat riwayat transaksi...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom styling untuk dark mode compatibility */
.card {
    transition: all 0.2s ease;
}

.table-responsive {
    border-radius: 0.475rem;
}

.symbol-label {
    transition: all 0.2s ease;
}

/* Hover effects */
.table tbody tr:hover {
    background-color: var(--bs-gray-100);
}

[data-bs-theme="dark"] .table tbody tr:hover {
    background-color: var(--bs-gray-800);
}

/* Dark mode specific adjustments */
[data-bs-theme="dark"] .text-gray-800 {
    color: var(--bs-gray-300) !important;
}

[data-bs-theme="dark"] .text-gray-600 {
    color: var(--bs-gray-400) !important;
}

[data-bs-theme="dark"] .border-gray-200 {
    border-color: var(--bs-gray-700) !important;
}
</style>