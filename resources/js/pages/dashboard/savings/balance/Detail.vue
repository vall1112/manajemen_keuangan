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
</script>

<template>
    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Detail Tabungan Siswa</h2>
        </div>

        <div class="card-body" v-if="!isLoading && data">
            <!-- Identitas Siswa -->
            <div class="mb-5">
                <h4 class="fw-bold">Identitas Siswa</h4>
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th>Nama</th>
                            <td>{{ data.student.nama }}</td>
                        </tr>
                        <tr>
                            <th>NIS</th>
                            <td>{{ data.student.nis }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ data.student.jenis_kelamin === "L" ? "Laki-laki" : "Perempuan" }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ data.student.classroom?.nama_kelas ?? "-" }}</td>
                        </tr>
                        <tr>
                            <th>Jurusan</th>
                            <td>{{ data.student.classroom?.major?.nama_jurusan ?? "-" }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    :class="data.student.status === 'Aktif' ? 'badge bg-success' : 'badge bg-secondary'">
                                    {{ data.student.status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Ringkasan Saldo -->
            <div class="mb-5">
                <h4 class="fw-bold">Saldo Terakhir</h4>
                <p class="fs-4 fw-bold text-primary">
                    Rp {{ Number(data.last_balance ?? 0).toLocaleString("id-ID") }}
                </p>
            </div>

            <!-- Riwayat Tabungan -->
            <div>
                <h4 class="fw-bold">Riwayat Tabungan</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Saldo</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in data.savings" :key="item.id">
                            <td>{{ new Date(item.created_at).toLocaleString("id-ID") }}</td>
                            <td>
                                <span :class="item.jenis === 'Setor' ? 'text-success fw-bold' : 'text-danger fw-bold'">
                                    {{ item.jenis }}
                                </span>
                            </td>
                            <td>Rp {{ Number(item.nominal).toLocaleString("id-ID") }}</td>
                            <td>Rp {{ Number(item.saldo).toLocaleString("id-ID") }}</td>
                            <td>{{ item.keterangan ?? "-" }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-else class="p-5 text-center">Memuat data...</div>
    </div>
</template>
