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
    column.accessor("nis", {
        header: "NIS",
    }),
    column.accessor("jenis_kelamin", {
        header: "Gender",
        cell: (cell) => {
            const val = cell.getValue();
            if (val === "L") return "Laki-laki";
            if (val === "P") return "Perempuan";
            return "-";
        },
    }),
    column.accessor("classroom_id", {
        header: "Kelas",
        cell: (info) => info.row.original.classroom?.nama_kelas ?? "-",
    }),
    column.accessor("status", {
        header: "Status",
        cell: (cell) => {
            const value = cell.getValue() ?? "";
            let colorClass = "text-muted";

            if (value === "Aktif") {
                colorClass = "text-success"; // hijau
            } else if (value === "Prakerin") {
                colorClass = "text-warning"; // kuning
            } else if (value === "Alumni") {
                colorClass = "text-dark"; // hitam
            } else if (value === "Keluar") {
                colorClass = "text-danger"; // merah
            }

            return h("span", { class: colorClass }, value);
        },
    }),
    column.accessor("foto", {
        header: "Foto",
        cell: (cell) => {
            const fotoUrl = cell.getValue();
            if (!fotoUrl)
                return h("div", { class: "text-muted fst-italic" }, "Tidak ada foto");

            return h("div", { class: "text-wrap" }, [
                h("img", {
                    src: `/storage/${fotoUrl}`,
                    alt: "Student",
                    style: {
                        width: "50px",
                        height: "50px",
                        objectFit: "cover",
                        borderRadius: "4px",
                        cursor: "pointer",
                    },
                    onClick: () => openImageModal(fotoUrl),
                    class: "student-image",
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
                            deleteTeacher(`/master/students/${cell.getValue()}`),
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
            <h2 class="mb-0">Daftar Siswa</h2>
            <button type="button" class="btn btn-sm btn-primary ms-auto" v-if="!openForm" @click="openForm = true">
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-students" url="/master/students" :columns="columns"></paginate>
        </div>
    </div>
</template>
