<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Transaction } from "@/types"; // ubah ke Transaction

const column = createColumnHelper<Transaction>();
const paginateRef = ref<any>(null);
const selected = ref<number | null>(null);
const openForm = ref<boolean>(false);

const { delete: deleteTransaction } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const columns = [
    column.accessor("no", {
        header: "#",
    }),
    column.accessor("tagihan_id", {
        header: "Tagihan",
        cell: (info) => info.row.original.bill?.student?.nama ?? "-", 
        // kalau mau tampilkan nama siswa dari tagihan
    }),
    column.accessor("nominal", {
        header: "Nominal",
        cell: (info) =>
            new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 2,
            }).format(info.getValue() ?? 0),
    }),
    column.accessor("metode", {
        header: "Metode",
    }),
    column.accessor("bukti", {
        header: "Bukti",
        cell: (info) =>
            info.getValue()
                ? h("a", { href: info.getValue(), target: "_blank" }, "Lihat")
                : "-",
    }),
    column.accessor("status", {
        header: "Status",
        cell: (info) => {
            const status = info.getValue();
            const badgeClass =
                status === "lunas"
                    ? "badge bg-success"
                    : status === "pending"
                    ? "badge bg-warning"
                    : "badge bg-secondary";
            return h("span", { class: badgeClass }, status ?? "-");
        },
    }),
    column.accessor("keterangan", {
        header: "Keterangan",
    }),
    column.accessor("id", {
        header: "Aksi",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-info",
                        onClick: () => {
                            selected.value = cell.getValue();
                            openForm.value = true;
                        },
                    },
                    h("i", { class: "la la-pencil fs-2" })
                ),
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () =>
                            deleteTransaction(`/transactions/${cell.getValue()}`),
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
    }),
];

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = null;
    }
    window.scrollTo(0, 0);
});
</script>

<template>
    <Form :selected="selected" @close="openForm = false" v-if="openForm" @refresh="refresh" />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Daftar Transaksi</h2>
            <button type="button" class="btn btn-sm btn-primary ms-auto" v-if="!openForm" @click="openForm = true">
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-transactions" url="/transactions" :columns="columns"></paginate>
        </div>
    </div>
</template>
