<script setup lang="ts">
import { ref, watch } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Saving } from "@/types";

const column = createColumnHelper<Saving>();
const paginateRef = ref<any>(null);

const columns = [
    column.accessor("no", {
        header: "#",
    }),
    column.accessor("student", {
        header: "Nama",
        cell: (info) => info.row.original.student?.nama ?? "-",
    }),
     column.accessor("student.classroom.nama_kelas", {
        header: "Kelas",
        cell: (info) => info.row.original.student?.classroom?.nama_kelas ?? "-",
    }),
    // column.accessor("jenis", {
    //     header: "Jenis",
    // }),
    column.accessor("saldo", {
        header: "Saldo",
        cell: (cell) => {
            const value = cell.getValue();
            return `Rp ${Number(value).toLocaleString("id-ID", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })}`;
        },
    }),
    // column.accessor("created_at", {
    //     header: "Tanggal",
    //     cell: (info) => {
    //         const date = new Date(info.getValue());
    //         const day = date.toLocaleDateString("id-ID", { weekday: "long" });
    //         const dayMonthYear = date.toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" });
    //         const time = date.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" });
    //         const formattedTime = time.replace(":", ".");

    //         return `${day}, ${dayMonthYear} jam ${formattedTime}`;
    //     },
    // }),
];

const refresh = () => paginateRef.value.refetch();
</script>

<template>
    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Saldo Tabungan</h2>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-savings" url="/savings/balances" :columns="columns"></paginate>
        </div>
    </div>
</template>
