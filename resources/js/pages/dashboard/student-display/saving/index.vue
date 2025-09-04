<script setup lang="ts">
import { ref, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Saving } from "@/types";
import { useDelete } from "@/libs/hooks";
import { useRouter } from "vue-router";

const column = createColumnHelper<Saving>();
const paginateRef = ref<any>(null);
const router = useRouter();

const { delete: deleteBalance } = useDelete({
  onSuccess: () => paginateRef.value.refetch(),
});

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
  column.accessor("id", {
    header: "Aksi",
    cell: (cell) => {
      const studentId = cell.row.original.student?.id; // ambil id student
      return h("div", { class: "d-flex gap-2" }, [
        // tombol detail
        h(
          "button",
          {
            class: "btn btn-sm btn-icon btn-primary",
            onClick: () => router.push({ name: "savings.detail", params: { id: studentId } }),
          },
          h("i", { class: "la la-eye fs-2" })
        ),
      ]);
    },
  }),
];

const refresh = () => paginateRef.value.refetch();
</script>

<template>
    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Saldo Tabungan</h2>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-savings" url="/student/savings/balances" :columns="columns"></paginate>
        </div>
    </div>
</template>
