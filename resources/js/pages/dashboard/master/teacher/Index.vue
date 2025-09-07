<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Teacher } from "@/types";

const column = createColumnHelper<Teacher>();
const paginateRef = ref<any>(null);
const selected = ref<number | null>(null);
const openForm = ref<boolean>(false);
const showImageModal = ref(false);
const selectedImage = ref("");

const openImageModal = (url: string) => {
    selectedImage.value = `/storage/${url}`;
    showImageModal.value = true;
};

const closeImageModal = () => {
    showImageModal.value = false;
    selectedImage.value = "";
};

const { delete: deleteTeacher } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const columns = [
    column.accessor("no", {
        header: "#",
    }),
    column.accessor("nama", {
        header: "Nama",
    }),
    column.accessor("nip", {
        header: "NIP",
    }),
    column.accessor("jabatan", {
        header: "Jabatan",
    }),
    // column.accessor("mata_pelajaran", {
    //     header: "Mapel",
    //     cell: ({ row }) => {
    //         const mapel = row?.original?.mata_pelajaran?.trim();
    //         return mapel ? mapel : "-";
    //     },
    // }),
    column.accessor("status", {
        header: "Status",
        cell: (cell) => {
            const value = cell.getValue() ?? "";
            let colorClass = "text-muted";

            if (value === "Aktif") {
                colorClass = "text-success"; // hijau
            } else if (value === "Cuti") {
                colorClass = "text-warning"; // kuning
            } else if (value === "Tidak Aktif") {
                colorClass = "text-danger"; // merah
            }

            return h("span", { class: colorClass }, value);
        },
    }),
    column.accessor("foto", {
        header: "Foto",
        cell: (cell) => {
            const fotoUrl = cell.getValue();

            const src = fotoUrl ? `/storage/${fotoUrl}` : "/media/avatars/blank.png";

            return h("div", { class: "text-wrap" }, [
                h("img", {
                    src,
                    alt: "Teacher",
                    style: {
                        width: "50px",
                        height: "50px",
                        objectFit: "cover",
                        borderRadius: "4px",
                        cursor: "pointer",
                    },
                    onClick: () => openImageModal(src),
                    class: "teacher-image",
                }),
            ]);
        },
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
    <Form :selected="selected" @close="openForm = false" v-if="openForm" @refresh="refresh" />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Daftar Guru</h2>
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
