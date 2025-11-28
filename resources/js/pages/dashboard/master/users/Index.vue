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

// ✅ Fungsi untuk mencetak kartu login
const printCard = async (userId: number) => {
    try {
        const url = `/api/master/user/${userId}/card`;
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
        toast.error("Gagal mencetak kartu login pengguna!");
    }
};

// ✅ Kolom tabel pengguna
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
    column.accessor("email", {
        header: "Email",
    }),
    column.accessor("photo", {
        header: "Foto",
        cell: (cell) => {
            const photoUrl = cell.getValue();
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
                    },
                }),
            ]);
        },
    }),
    column.accessor("id", {
        header: "Aksi",
        cell: (info) => {
            const user = info.row.original;
            return h("div", { class: "d-flex gap-2" }, [
                // Tombol Edit
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-info",
                        onClick: () => {
                            selected.value = user.uuid;
                            openForm.value = true;
                        },
                        title: "Edit Pengguna",
                    },
                    h("i", { class: "la la-pencil fs-2" })
                ),

                // Tombol Cetak Kartu Login
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-warning",
                        onClick: () => printCard(user.id),
                        title: "Cetak Kartu Login",
                    },
                    h("i", { class: "la la-print fs-2" })
                ),

                // Tombol Hapus
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () => deleteUser(`/master/users/${user.uuid}`),
                        title: "Hapus Pengguna",
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]);
        },
    }),
];

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) selected.value = "";
    window.scrollTo(0, 0);
});
</script>

<template>
    <Form :selected="selected" @close="openForm = false" v-if="openForm" @refresh="refresh" />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Daftar Pengguna</h2>
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