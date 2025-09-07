<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement } from "chart.js";
import { Bar } from "vue-chartjs";

ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement);

const router = useRouter();

interface DashboardResponse {
  statistics: {
    students: number;
    users: number;
    classrooms: number;
    majors: number;
    roles: number;
    school_years: number;
    payment_type: number;
    students_per_major: { jurusan: string; jumlah: number }[];
    students_per_class: { kelas: string; jumlah: number }[];
  };
}

const dashboardData = ref<DashboardResponse | null>(null);
const isLoading = ref(true);

const formatNumber = (val: number) => new Intl.NumberFormat("id-ID").format(val);

const goTo = (route: string) => {
  if (router.currentRoute.value.path !== route) router.push(route);
};

// Statistik master data
const statisticCards = computed(() => {
  if (!dashboardData.value) return [];
  const data = dashboardData.value.statistics;
  return [
    { title: "Siswa", value: formatNumber(data.students), icon: "bi bi-people-fill", gradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)", route: "/dashboard/master/students" },
    { title: "Pengguna", value: formatNumber(data.users), icon: "bi bi-person-check-fill", gradient: "linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)", route: "/dashboard/master/users" },
    { title: "Kelas", value: formatNumber(data.classrooms), icon: "bi bi-house-door-fill", gradient: "linear-gradient(135deg, #5ee7df 0%, #b490ca 100%)", route: "/dashboard/master/classrooms" },
    { title: "Jurusan", value: formatNumber(data.majors), icon: "bi bi-mortarboard-fill", gradient: "linear-gradient(135deg, #f6d365 0%, #fda085 100%)", route: "/dashboard/master/majors" },
    { title: "Role", value: formatNumber(data.roles), icon: "bi bi-shield-lock-fill", gradient: "linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)", route: "/dashboard/master/users/roles" },
    { title: "Tahun Ajaran", value: formatNumber(data.school_years), icon: "bi bi-calendar2-week-fill", gradient: "linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%)", route: "/dashboard/master/school_years" },
    { title: "Jenis Pembayaran", value: formatNumber(data.payment_type), icon: "bi bi-credit-card-2-front-fill", gradient: "linear-gradient(135deg, #43cea2 0%, #185a9d 100%)", route: "/dashboard/master/payment_types" }
  ];
});

// Chart data
const chartData = ref({
  labels: [] as string[],
  datasets: [{ label: "Jumlah Siswa", data: [] as number[], backgroundColor: "#4facfe" }]
});
const chartClassData = ref({
  labels: [] as string[],
  datasets: [{ label: "Jumlah Siswa", data: [] as number[], backgroundColor: "#43e97b" }]
});

const chartOptions = {
  responsive: true,
  plugins: { legend: { display: false }, title: { display: true, text: "Siswa per Jurusan" } },
  scales: { y: { beginAtZero: true } }
};
const chartClassOptions = {
  responsive: true,
  plugins: { legend: { display: false }, title: { display: true, text: "Siswa per Kelas" } },
  scales: { y: { beginAtZero: true } }
};

// Fetch dashboard data
const fetchDashboard = async () => {
  isLoading.value = true;
  try {
    const res = await axios.get("/dashboard/admin");
    dashboardData.value = res.data;

    const studentsPerMajor = dashboardData.value.statistics.students_per_major;
    chartData.value.labels = studentsPerMajor.map(m => m.jurusan);
    chartData.value.datasets[0].data = studentsPerMajor.map(m => m.jumlah);

    const studentsPerClass = dashboardData.value.statistics.students_per_class;
    chartClassData.value.labels = studentsPerClass.map(c => c.kelas);
    chartClassData.value.datasets[0].data = studentsPerClass.map(c => c.jumlah);

  } catch (error) {
    console.error("Error fetching dashboard data:", error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => fetchDashboard());
</script>

<template>
  <div v-if="isLoading" class="p-5 text-center">
    <div class="spinner-border text-primary"></div>
  </div>

  <div v-else-if="dashboardData" class="p-4">
    <!-- Statistik Master Data -->
    <div class="row mb-5">
      <div v-for="card in statisticCards" :key="card.title" class="col-md-6 col-lg-4 col-xl-3 mb-4">
        <div class="card h-100 cursor-pointer text-white" :style="{ background: card.gradient }" role="button"
          @click="goTo(card.route)">
          <div class="card-body d-flex align-items-center">
            <div class="symbol symbol-60px me-4">
              <div class="symbol-label d-flex align-items-center justify-content-center text-white">
                <i :class="`${card.icon} fs-2x`"></i>
              </div>
            </div>
            <div>
              <div class="fs-2hx fw-bold">{{ card.value }}</div>
              <div class="fw-semibold fs-6 mt-1">{{ card.title }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Distribusi Siswa per Jurusan & Kelas -->
    <div class="row mb-5">
      <div class="col-lg-6 mb-4">
        <div class="card h-100">
          <div class="card-header"><h3 class="card-title">Siswa per Jurusan</h3></div>
          <div class="card-body"><Bar :data="chartData" :options="chartOptions" /></div>
        </div>
      </div>

      <div class="col-lg-6 mb-4">
        <div class="card h-100">
          <div class="card-header"><h3 class="card-title">Siswa per Kelas</h3></div>
          <div class="card-body"><Bar :data="chartClassData" :options="chartClassOptions" /></div>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="text-center p-5">
    <p class="text-gray-500">Data tidak tersedia. Silakan refresh halaman.</p>
    <button class="btn btn-primary" @click="fetchDashboard()">Refresh</button>
  </div>
</template>
