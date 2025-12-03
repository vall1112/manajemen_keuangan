<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Student } from "@/types";
import { toast } from "vue3-toastify";

const column = createColumnHelper<Student>();
const paginateRef = ref<any>(null);
const selected = ref<number | null>(null);
const openForm = ref<boolean>(false);
const showImageModal = ref(false);
const selectedImage = ref("");

// =============================
// IMAGE MODAL
// =============================
const openImageModal = (url: string) => {
    selectedImage.value = `/storage/${url}`;
    showImageModal.value = true;
};

const closeImageModal = () => {
    showImageModal.value = false;
    selectedImage.value = "";
};

// =============================
// DELETE STUDENT
// =============================
const { delete: deleteStudent } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

// =============================
// CETAK KARTU LOGIN
// =============================
const printCard = async (studentId: number) => {
    try {
        const url = `/api/master/student/${studentId}/card`;
        const response = await fetch(url);
        const cardHtml = await response.text();

        const printWindow = window.open("", "PRINT", "width=900,height=650");
        if (printWindow) {
            printWindow.document.write(cardHtml);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.onafterprint = () => printWindow.close();
        }
    } catch (error) {
        console.error("Gagal mencetak kartu:", error);
        toast.error("Gagal mencetak kartu login siswa!");
    }
};

// =============================
// TABLE COLUMNS
// =============================
const columns = [
    column.accessor("no", { header: "#" }),
    column.accessor((row) => row.user?.username ?? "-", {
        id: "username",
        header: "Username",
    }), column.accessor("nama", { header: "Nama" }),
    column.accessor("nis", { header: "NIS" }),
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
            if (value === "Aktif") colorClass = "text-success";
            else if (value === "Prakerin") colorClass = "text-warning";
            else if (value === "Alumni") colorClass = "text-dark";
            else if (value === "Keluar") colorClass = "text-danger";
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
                    alt: "Student",
                    style: {
                        width: "50px",
                        height: "50px",
                        objectFit: "cover",
                        borderRadius: "4px",
                        cursor: "pointer",
                    },
                    onClick: () => openImageModal(src),
                    class: "student-image",
                }),
            ]);
        },
    }),
    column.accessor("id", {
        header: "Aksi",
        cell: (cell) => {
            const student = cell.row.original;
            return h("div", { class: "d-flex gap-2" }, [
                // Tombol Cetak Kartu Login
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-warning",
                        onClick: () => {
                            if (!student.user?.id) {
                                toast.warning("Siswa belum memiliki akun user!");
                                return;
                            }
                            printCard(student.id);
                        },
                        title: "Cetak Kartu Login",
                    },
                    h("i", { class: "la la-print fs-2" })
                ),
                // Tombol Edit
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-info",
                        onClick: () => {
                            selected.value = student.id;
                            openForm.value = true;
                        },
                    },
                    h("i", { class: "la la-pencil fs-2" })
                ),
                // Tombol Hapus
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () => deleteStudent(`/master/students/${student.id}`),
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]);
        },
    }),
];

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) selected.value = null;
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

    <!-- Modal Image -->
    <modal v-model:show="showImageModal">
        <img :src="selectedImage" alt="Preview" class="w-100" />
    </modal>
</template>
