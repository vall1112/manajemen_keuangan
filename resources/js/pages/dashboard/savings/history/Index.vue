<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";

import { createColumnHelper } from "@tanstack/vue-table";
import type { Teacher } from "@/types";

const column = createColumnHelper<Teacher>();
const paginateRef = ref<any>(null);
const selected = ref<number | null>(null);
const openForm = ref<boolean>(false);

const { delete: deleteTeacher } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const columns = [
    column.accessor("id", {
        header: "#",
    }),
    column.accessor("nama", {
        header: "Nama",
    }),
    column.accessor("no_telepon", {
        header: "No. Telp",
    }),
    column.accessor("jabatan", {
        header: "Jabatan",
        cell: ({ row }) => {
            const jabatan = row.original.jabatan || "";
            const mapel = row.original.mata_pelajaran || "";

            return mapel
                ? `${jabatan} ( ${mapel} )`
                : jabatan;
        },
    }),
    column.accessor("status", {
        header: "Status",
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
                            deleteTeacher(`/master/teachers/${cell.getValue()}`),
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
    <Form selected="selected" @close="openForm = false" v-if="openForm" @refresh="refresh" />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Histori Tabungan</h2>
            <button type="button" class="btn btn-sm btn-primary ms-auto" v-if="!openForm" @click="openForm = true">
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-teachers" url="/master/teachers" :columns="columns"></paginate>
        </div>
    </div>
</template>
