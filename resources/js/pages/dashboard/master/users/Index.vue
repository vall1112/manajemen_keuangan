<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { User } from "@/types";
import { toast } from "vue3-toastify";
import axios from "axios";

const column = createColumnHelper<User>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");
const openForm = ref<boolean>(false);

const { delete: deleteUser } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const columns = [
    column.accessor("no", {
        header: "#",
    }),
    column.accessor("username", {
        header: "Username",
    }),
    column.accessor("name", {
        header: "Nama",
    }),
    column.accessor("student_id", {
        header: "Siswa",
        cell: (info) => info.row.original.student?.nama ?? "-",
    }),
    column.accessor("email", {
        header: "Email",
    }),
    // column.accessor("toggle", {
    //     header: () => h("div", { class: "text-center" }, "Status"),
    //     cell: (cell) => {
    //         const row = cell.row.original;
    //         return h("div", { class: "text-center" }, [
    //             h(
    //                 "label",
    //                 {
    //                     class:
    //                         "form-check form-switch form-check-custom form-check-solid d-inline-flex justify-content-center",
    //                 },
    //                 [
    //                     h("input", {
    //                         type: "checkbox",
    //                         checked: row.status === "Aktif",
    //                         class: "form-check-input",
    //                         onChange: async (e: Event) => {
    //                             const checkbox = e.target as HTMLInputElement;
    //                             const newStatus =
    //                                 row.status === "Aktif" ? "Tidak Aktif" : "Aktif";
    //                             checkbox.disabled = true;

    //                             try {
    //                                 const response = await axios.put(
    //                                     `/master/users/${row.id}/status`,
    //                                     { status: newStatus }
    //                                 );
    //                                 toast.success(
    //                                     response.data.message || "Status berhasil diperbarui!"
    //                                 );
    //                                 paginateRef.value?.refetch();
    //                             } catch (error: any) {
    //                                 toast.error(
    //                                     error.response?.data?.message ||
    //                                     "Gagal memperbarui status!"
    //                                 );
    //                                 checkbox.checked = !checkbox.checked;
    //                             } finally {
    //                                 checkbox.disabled = false;
    //                             }
    //                         },
    //                     }),
    //                 ]
    //             ),
    //         ]);
    //     },
    // }),
    column.accessor("photo", {
        header: "Foto",
        cell: (cell) => {
            const photoUrl = cell.getValue();

            // fallback jika photo null/undefined/empty
            const src = photoUrl ? `/storage/${photoUrl}` : "/media/avatars/blank.png";

            return h("div", { class: "text-wrap" }, [
                h("img", {
                    src,
                    alt: "User",
                    style: {
                        width: "50px",
                        height: "50px",
                        objectFit: "cover",
                        borderRadius: "4px",
                        cursor: photoUrl ? "pointer" : "default",
                    },
                    onClick: () => {
                        if (photoUrl) openImageModal(src);
                    },
                    class: "user-image",
                }),
            ]);
        },
    }),
    column.accessor("uuid", {
        header: "Aksi",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-success",
                        onClick: () => {
                            selected.value = cell.getValue();
                            openForm.value = true;
                        },
                    },
                    h("i", { class: "la la-print fs-2" })
                ),
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
                            deleteUser(`/master/users/${cell.getValue()}`),
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
    }),
];

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = "";
    }
    window.scrollTo(0, 0);
});
</script>

<template>
    <Form :selected="selected" @close="openForm = false" v-if="openForm" @refresh="refresh" />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Daftar Pengguna Siswa</h2>
            <button type="button" class="btn btn-sm btn-primary ms-auto" v-if="!openForm" @click="openForm = true">
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-users" url="/master/users" :columns="columns"></paginate>
        </div>
    </div>
</template>
