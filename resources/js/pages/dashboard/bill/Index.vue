<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Bill } from "@/types";
import { useRouter } from "vue-router";

const router = useRouter();
const column = createColumnHelper<Bill>();
const paginateRef = ref<any>(null);
const selected = ref<number | null>(null);
const openForm = ref<boolean>(false);

const { delete: deleteBill } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const columns = [
    column.accessor("no", {
        header: "#",
    }),
    column.accessor("kode", {
        header: "Kode",
    }),
    column.accessor("student_id", {
        header: "Nama",
        cell: (info) => info.row.original.student?.nama ?? "-",
    }),
    column.accessor("payment_type_id", {
        header: "Jenis Pembayaran",
        cell: (info) => info.row.original.payment_type?.nama_jenis ?? "-",
    }),
    column.accessor("school_year_id", {
        header: "Tahun Ajaran",
        cell: (info) => info.row.original.school_year?.tahun_ajaran ?? "-",
    }),
    column.accessor("total_tagihan", {
        header: "Total Tagihan",
        cell: (info) =>
            new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 2,
            }).format(info.getValue() ?? 0),
    }),
    column.accessor("tanggal_tagih", {
        header: "Tanggal Tagih",
        cell: (info) =>
            info.getValue()
                ? new Date(info.getValue()).toLocaleDateString("id-ID", {
                    day: "2-digit",
                    month: "long",
                    year: "numeric",
                })
                : "-",
    }),
    column.accessor("jatuh_tempo", {
        header: "Jatuh Tempo",
        cell: (info) =>
            info.getValue()
                ? new Date(info.getValue()).toLocaleDateString("id-ID", {
                    day: "2-digit",
                    month: "long",
                    year: "numeric",
                })
                : "-",
    }),
    column.accessor("status", {
        header: "Status",
        cell: (info) => {
            const status = info.getValue();
            let statusClass = "";

            if (status === "Lunas") {
                statusClass = "text-success"; // Hijau
            } else if (status === "Pending") {
                statusClass = "text-warning"; // Kuning
            } else if (status === "Belum Dibayar") {
                statusClass = "text-danger"; // Merah
            } else {
                statusClass = "text-muted"; // Default (abu-abu)
            }

            return h("span", { class: `fw-semibold ${statusClass}` }, status);
        },
    }),
    column.accessor("id", {
        header: "Aksi",
        cell: (cell) => {
            const row = cell.row.original;
            const status = row.status;

            // Hanya tampilkan tombol bayar jika status adalah "Belum dibayar"
            if (status !== "Belum Dibayar") {
                return h("div", { class: "d-flex gap-2" }, [
                    h("span", { class: "text-muted fst-italic" }, "-")
                ]);
            }

            return h("div", { class: "d-flex gap-2" }, [
                h(
                    "button",
                    {
                        class:
                            "btn btn-sm btn-light-success d-flex align-items-center",
                        title: "Bayar Tagihan",
                        onClick: () => {
                            const kodeTagihan = row.kode;
                            router.push({
                                name: "form.transaction",
                                query: { kode: kodeTagihan },
                            });
                        },
                    },
                    [
                        h("i", { class: "la la-credit-card fs-2" }),
                        h("span", "Bayar"),
                    ]
                ),
            ]);
        },
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
            <h2 class="mb-0">Daftar Tagihan</h2>
            <button type="button" class="btn btn-sm btn-primary ms-auto" v-if="!openForm" @click="openForm = true">
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-bills" url="/bills" :columns="columns"></paginate>
        </div>
    </div>
</template>