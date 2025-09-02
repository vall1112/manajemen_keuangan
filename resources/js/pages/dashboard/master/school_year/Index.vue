<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { toast } from "vue3-toastify";
import { createColumnHelper } from "@tanstack/vue-table";
import type { SchoolYear } from "@/types";

const column = createColumnHelper<SchoolYear>();
const paginateRef = ref<any>(null);
const selected = ref<number | null>(null);
const openForm = ref<boolean>(false);

const { delete: deleteTeacher } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const columns = [
    column.accessor("no", {
        header: "#",
    }),
    column.accessor("tahun_ajaran", {
        header: "Tahun Ajaran",
    }),
    column.accessor("semester", {
        header: "Semester",
    }),
    column.accessor("toggle", {
        header: () =>
            h("div", { class: "text-center" }, "Status"),
        cell: (cell) => {
            const row = cell.row.original;
            return h("div", { class: "text-center" }, [
                h(
                    "label",
                    {
                        class:
                            "form-check form-switch form-check-custom form-check-solid d-inline-flex justify-content-center",
                    },
                    [
                        h("input", {
                            type: "checkbox",
                            checked: row.status === "Aktif",
                            class: "form-check-input",
                            onChange: async (e: Event) => {
                                const newStatus =
                                    row.status === "Aktif" ? "Tidak Aktif" : "Aktif";
                                const checkbox = e.target as HTMLInputElement;
                                checkbox.disabled = true;
                                try {
                                    const response = await axios.put(
                                        `/school-years/${row.id}/status`,
                                        {
                                            status: newStatus,
                                        }
                                    );
                                    toast.success(
                                        response.data.message || "Status berhasil diperbarui!"
                                    );
                                    paginateRef.value?.refetch();
                                } catch (error: any) {
                                    toast.error(
                                        error.response?.data?.message ||
                                        "Gagal memperbarui status!"
                                    );
                                    checkbox.checked = !checkbox.checked;
                                } finally {
                                    checkbox.disabled = false;
                                }
                            },
                        }),
                        h("span", { class: "form-check-label" }),
                    ]
                ),
            ]);
        },
    }),
    // column.accessor("id", {
    //     header: "Aksi",
    //     cell: (cell) =>
    //         h("div", { class: "d-flex gap-2" }, [
    //             h(
    //                 "button",
    //                 {
    //                     class: "btn btn-sm btn-icon btn-info",
    //                     onClick: () => {
    //                         selected.value = cell.getValue();
    //                         openForm.value = true;
    //                     },
    //                 },
    //                 h("i", { class: "la la-pencil fs-2" })
    //             ),
    //             h(
    //                 "button",
    //                 {
    //                     class: "btn btn-sm btn-icon btn-danger",
    //                     onClick: () =>
    //                         deleteTeacher(`/master/school-years/${cell.getValue()}`),
    //                 },
    //                 h("i", { class: "la la-trash fs-2" })
    //             ),
    //         ]),
    // }),
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
            <h2 class="mb-0">Daftar Tahun Ajaran</h2>
            <button type="button" class="btn btn-sm btn-primary ms-auto" v-if="!openForm" @click="openForm = true">
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-schoolYears" url="/master/school-years" :columns="columns">
            </paginate>
        </div>
    </div>
</template>
