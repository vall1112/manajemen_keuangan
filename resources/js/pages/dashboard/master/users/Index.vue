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
const handlePrint = async (id: number) => {
    try {
        const { data: user } = await axios.get(`/users/${id}/print`);

        // Buat HTML kartu ulangan
        const htmlContent = `
        <html>
        <head>
            <title>Kartu Ujian ${user.name}</title>
            <style>
                @page {
                    size: landscape;
                    margin: 10mm;
                }
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                }
                .card {
                    width: 900px;
                    border: 1px solid black;
                    padding: 12px;
                    display: flex;
                    flex-direction: column;
                }
                .header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 1px solid black;
                    padding-bottom: 4px;
                    margin-bottom: 4px;
                }
                .header img {
                    width: 50px;
                }
                .header div {
                    text-align: center;
                    flex: 1;
                }
                .content {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                }
                .info {
                    flex: 1;
                }
                .info table {
                    line-height: 1.6;
                }
                .photo {
                    width: 100px;
                    height: 100px;
                    border: 1px solid black;
                    background: #eee;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-top: 10px;
                }
                .ttd {
                    text-align: right;
                    margin-top: 20px;
                }
                .qr {
                    text-align: right;
                }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="header">
                    <img src="/media/logo.png" alt="Logo">
                    <div>
                        <strong>KARTU PESERTA UJIAN SEKOLAH</strong><br>
                        DEMO E-UJIAN<br>
                        TAHUN PELAJARAN 2024/2025
                    </div>
                    <div>Panitia</div>
                </div>

                <div class="content">
                    <div class="info">
                        <table>
                            <tr><td>No Peserta</td><td>:</td><td>${user.id}</td></tr>
                            <tr><td>Nama</td><td>:</td><td>${user.name}</td></tr>
                            <tr><td>Username</td><td>:</td><td>${user.username}</td></tr>
                            <tr><td>Password</td><td>:</td><td>${user.password}</td></tr>
                        </table>
                        <div class="photo">
                            <img src="${user.photo ? `/storage/${user.photo}` : '/media/avatars/blank.png'}"
                                alt="Foto" width="100" height="100" />
                        </div>
                    </div>

                    <div class="qr">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=${user.id}" alt="QR">
                    </div>
                </div>

                <div class="ttd">
                    <p>Bantul, 13 Maret 2024<br>Kepala Sekolah,</p>
                    <br><br>
                    <p><strong>Kepala Sekolah</strong><br>NIP N123144345</p>
                </div>
            </div>
        </body>
        </html>`;

        // Buka jendela sementara untuk print
        const printWindow = window.open('', '_blank', 'width=1000,height=700');
        printWindow.document.open();
        printWindow.document.write(htmlContent);
        printWindow.document.close();

        // Tunggu sebentar sampai semua elemen termuat
        printWindow.onload = () => {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    } catch (error) {
        toast.error("Gagal memuat data kartu.");
    }
};

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
                        onClick: () => handlePrint(cell.row.original.id),
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
                // h(
                //     "button",
                //     {
                //         class: "btn btn-sm btn-icon btn-secondary",
                //         onClick: () => {
                //              window.location.href = (`/dashboard/master/users/print${cell.getValue()}`);
                //     },
                //     },
                // h("i", { class: "la la-print fs-2" })
                // ),
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
