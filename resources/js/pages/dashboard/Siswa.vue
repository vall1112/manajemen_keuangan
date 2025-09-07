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
    
const formatDate = (dateString: string) =>
    new Date(dateString).toLocaleDateString("id-ID", { 
        year: "numeric", 
        month: "short", 
        day: "numeric" 
    });

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

const getStatusColor = (status: string) => {
    return status === 'Belum Dibayar' ? 'danger' : 'success';
};

const getTransactionIcon = (type: string) => {
    return type.toLowerCase().includes('setor') || type.toLowerCase().includes('deposit') ? 
        'arrow-down' : 'arrow-up';
};

const getTransactionColor = (type: string) => {
    return type.toLowerCase().includes('setor') || type.toLowerCase().includes('deposit') ? 
        'success' : 'primary';
};

onMounted(() => fetchData());
</script>

<template>
    <!-- Loading State -->
    <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="min-height: 400px;">
        <div class="spinner-border spinner-border-lg text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Main Content -->
    <div v-else-if="data" class="d-flex flex-column flex-lg-row">
        
        <!-- Left Column - Profile -->
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-7 me-7 me-lg-10">
            
            <!-- Profile Card -->
            <div class="card mb-7">
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div class="symbol symbol-100px symbol-circle mb-7">
                        <img v-if="data.profile.foto" 
                             :src="data.profile.foto" 
                             :alt="data.profile.nama"
                             class="symbol-label" />
                        <div v-else class="symbol-label fs-3 bg-light-primary text-primary">
                            {{ data.profile.nama.charAt(0).toUpperCase() }}
                        </div>
                    </div>
                    
                    <!-- Name & Status -->
                    <div class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                        {{ data.profile.nama }}
                    </div>
                    <div class="fs-5 fw-semibold text-muted mb-6">
                        {{ data.profile.kelas }} - {{ data.profile.jurusan }}
                    </div>
                    
                    <!-- Stats -->
                    <div class="d-flex flex-wrap flex-center">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                            <div class="fs-4 fw-bold text-gray-700">
                                <span class="w-75px">{{ data.profile.nis }}</span>
                                <i class="ki-duotone ki-profile-user fs-2 text-primary ms-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </div>
                            <div class="fw-semibold text-muted">NIS</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details Card -->
            <div class="card mb-7">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">Detail Profil</h3>
                    </div>
                </div>
                <div class="card-body pt-5">
                    <!-- Email -->
                    <div class="d-flex align-items-center mb-5">
                        <div class="symbol symbol-40px me-4">
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-sms fs-2 text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="fw-bold fs-6">{{ data.profile.email }}</div>
                            <div class="fw-semibold text-gray-400">Email</div>
                        </div>
                    </div>
                    
                    <!-- Class -->
                    <div class="d-flex align-items-center mb-5">
                        <div class="symbol symbol-40px me-4">
                            <div class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-teacher fs-2 text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="fw-bold fs-6">{{ data.profile.kelas }}</div>
                            <div class="fw-semibold text-gray-400">Kelas</div>
                        </div>
                    </div>
                    
                    <!-- Major -->
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-4">
                            <div class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-graduation-cap fs-2 text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="fw-bold fs-6">{{ data.profile.jurusan }}</div>
                            <div class="fw-semibold text-gray-400">Jurusan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Dashboard Content -->
        <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
            
            <!-- Stats Cards -->
            <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                <!-- Total Saldo -->
                <div class="col-lg-6 col-xxl-4">
                    <div class="card h-md-50 mb-5 mb-xl-10">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6">Total Saldo</div>
                                <div class="ms-auto">
                                    <i class="ki-duotone ki-wallet fs-2 text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </div>
                            </div>
                            <div class="text-gray-900 fw-bold fs-2 mb-2 mt-5">
                                {{ formatCurrency(data.savings.saldo) }}
                            </div>
                            <div class="fw-semibold text-success">
                                <i class="ki-duotone ki-arrow-up fs-3 text-success me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Tabungan Aktif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Bills -->
                <div class="col-lg-6 col-xxl-4">
                    <div class="card h-md-50 mb-5 mb-xl-10">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6">Tagihan Tertunggak</div>
                                <div class="ms-auto">
                                    <i class="ki-duotone ki-bill fs-2 text-danger">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                </div>
                            </div>
                            <div class="text-gray-900 fw-bold fs-2 mb-2 mt-5">
                                {{ data.bills.filter(b => b.status === 'Belum Dibayar').length }}
                            </div>
                            <div class="fw-semibold text-danger">
                                <i class="ki-duotone ki-arrow-down fs-3 text-danger me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Item
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-6 g-xl-9">
                <!-- Bills Section -->
                <div class="col-lg-6">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-circle me-5">
                                        <div class="symbol-label bg-light-danger text-danger">
                                            <i class="ki-duotone ki-bill fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                                <span class="path6"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="fs-2 fw-bold text-dark">Tagihan</div>
                                        <div class="fs-6 fw-semibold text-gray-400">Daftar pembayaran</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <div class="timeline">
                                <div v-for="bill in data.bills.filter(b => b.status === 'Belum Dibayar')" 
                                     :key="bill.id"
                                     class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px me-4">
                                        <div class="symbol-label bg-light-danger">
                                            <i class="ki-duotone ki-abstract-47 fs-2 text-danger">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-5">
                                            <div class="fs-5 fw-semibold mb-2">{{ bill.title }}</div>
                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                <div class="text-muted me-2 fs-7">Jatuh tempo:</div>
                                                <div class="text-danger">{{ formatDate(bill.due_date) }}</div>
                                            </div>
                                        </div>
                                        <div class="overflow-auto pb-5">
                                            <div class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                                                <div class="flex-grow-1">
                                                    <div class="fs-6 text-gray-800 fw-semibold">Total Tagihan</div>
                                                </div>
                                                <div class="fs-4 text-danger fw-bold">{{ formatCurrency(bill.amount) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Empty State -->
                                <div v-if="data.bills.filter(b => b.status === 'Belum Dibayar').length === 0" 
                                     class="text-center py-10">
                                    <i class="ki-duotone ki-check-circle fs-3x text-success mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <div class="fs-4 fw-bold text-gray-700 mb-2">Tidak ada tagihan</div>
                                    <div class="fs-6 text-gray-500">Semua tagihan sudah terbayar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Savings Section -->
                <div class="col-lg-6">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-circle me-5">
                                        <div class="symbol-label bg-light-success text-success">
                                            <i class="ki-duotone ki-wallet fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="fs-2 fw-bold text-dark">Tabungan</div>
                                        <div class="fs-6 fw-semibold text-gray-400">Riwayat transaksi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <!-- Current Balance -->
                            <div class="d-flex flex-stack bg-success bg-opacity-25 rounded border border-success border-dashed p-6 mb-7">
                                <div class="fs-6 fw-bold text-gray-700">
                                    <i class="ki-duotone ki-wallet text-success fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                    Saldo Saat Ini
                                </div>
                                <div class="fs-4 fw-bold text-success">{{ formatCurrency(data.savings.saldo) }}</div>
                            </div>

                            <!-- Transactions -->
                            <div class="timeline">
                                <div v-for="transaction in data.savings.transactions.slice(0, 5)" 
                                     :key="transaction.id"
                                     class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div :class="`symbol-label bg-light-${getTransactionColor(transaction.type)}`">
                                            <i :class="`ki-duotone ki-${getTransactionIcon(transaction.type)} fs-2 text-${getTransactionColor(transaction.type)}`">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="overflow-auto pe-3">
                                            <div class="fs-5 fw-semibold mb-2">{{ transaction.type }}</div>
                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                <div class="text-muted me-2 fs-7">{{ formatDate(transaction.date) }}</div>
                                                <div :class="`badge badge-light-${getTransactionColor(transaction.type)} fs-8 px-3 py-2`">
                                                    {{ transaction.status }}
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
                                                <div :class="`fs-3 fw-bold text-${getTransactionColor(transaction.type)}`">
                                                    {{ formatCurrency(transaction.amount) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Empty State -->
                                <div v-if="data.savings.transactions.length === 0" 
                                     class="text-center py-10">
                                    <i class="ki-duotone ki-wallet fs-3x text-gray-400 mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                    <div class="fs-4 fw-bold text-gray-700 mb-2">Belum ada transaksi</div>
                                    <div class="fs-6 text-gray-500">Transaksi akan tampil di sini</div>
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
        <i class="ki-duotone ki-cross-circle fs-3x text-danger mb-5">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
        <div class="fs-3 fw-bold text-gray-700 mb-2">Data tidak tersedia</div>
        <div class="fs-6 text-gray-500 mb-7">Silakan refresh halaman untuk memuat ulang data</div>
        <button class="btn btn-primary" @click="fetchData()">
            <i class="ki-duotone ki-arrows-circle fs-2 me-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            Refresh Data
        </button>
    </div>
</template>