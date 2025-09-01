<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Bill } from "@/types";

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
    column.accessor("siswa_id", {
        header: "Siswa",
        cell: (info) => info.row.original.student?.nama ?? "-",
    }),
    column.accessor("jenis_pembayaran_id", {
        header: "Jenis Pembayaran",
        cell: (info) => info.row.original.paymentType?.nama ?? "-",
    }),
    // column.accessor("school_year_id", {
    //     header: "Tahun Ajaran",
    //     cell: (info) => info.row.original.schoolYear?.tahun_ajaran ?? "-",
    // }),
    column.accessor("total", {
        header: "Total",
        cell: (info) => new Intl.NumberFormat("id-ID").format(info.getValue() ?? 0),
    }),
    column.accessor("tanggal_tagih", {
        header: "Tagih",
    }),
    column.accessor("status", {
        header: "Status",
    }),
    // column.accessor("dibayar", {
    //     header: "Dibayar",
    //     cell: (info) => new Intl.NumberFormat("id-ID").format(info.getValue() ?? 0),
    // }),
    column.accessor("sisa", {
        header: "Sisa",
        cell: (info) => new Intl.NumberFormat("id-ID").format(info.getValue() ?? 0),
    }),
    // column.accessor("keterangan", {
    //     header: "Keterangan",
    //     cell: (info) => info.getValue() || "-",
    // }),
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
                            deleteBill(`/master/bills/${cell.getValue()}`),
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
    <Form
        :selected="selected"
        @close="openForm = false"
        v-if="openForm"
        @refresh="refresh"
    />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Daftar Tagihan</h2>
            <button
                type="button"
                class="btn btn-sm btn-primary ms-auto"
                v-if="!openForm"
                @click="openForm = true"
            >
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-bills"
                url="/master/bills"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>
