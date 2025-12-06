<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Transaction } from "@/types";
import axios from "@/libs/axios";
import Swal from "sweetalert2";

// Tambahkan router
import { useRouter } from "vue-router";
const router = useRouter();

const emit = defineEmits(["close", "refresh"]);
const column = createColumnHelper<Transaction>();
const paginateRef = ref<any>(null);
const selected = ref<number | null>(null);
const openForm = ref<boolean>(false);

const { delete: deleteTransaction } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

// Buka halaman Blade struk di tab baru
const printReceipt = async (transactionId: number) => {
    try {
        const url = `/api/transactions/${transactionId}/receipt`;

        const response = await fetch(url);
        const receiptHtml = await response.text();

        const printWindow = window.open("", "PRINT", "width=900,height=650");

        if (printWindow) {
            printWindow.document.write(receiptHtml);
            printWindow.document.close();

            printWindow.focus();
            printWindow.print();
            printWindow.onafterprint = () => printWindow.close();
        }
    } catch (error) {
        console.error("Gagal cetak struk:", error);
    }
};

const columns = [
    column.accessor("no", { header: "#" }),
    column.accessor("kode", { header: "Receipt" }),
    column.accessor("student_name", { header: "Nama" }),
    column.accessor("payment_type_name", { header: "Jenis Pembayaran" }),
    column.accessor("nominal", {
        header: "Nominal",
        cell: (info) =>
            new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 2,
            }).format(info.getValue() ?? 0),
    }),
    column.accessor("created_at", {
        header: "Tanggal Bayar",
        cell: (info) => {
            const date = new Date(info.getValue() as string);
            return new Intl.DateTimeFormat("id-ID", {
                day: "numeric",
                month: "long",
                year: "numeric",
            }).format(date);
        },
    }),
    column.accessor("id", {
        header: "Aksi",
        cell: (info) => {
            const transaction = info.row.original;

            return h(
                "button",
                {
                    class: "btn btn-sm btn-success",
                    onClick: async () => {
                        const result = await Swal.fire({
                            icon: "warning",
                            text: "Apakah Anda yakin ingin mengonfirmasi pembayaran ini?",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Konfirmasi",
                            cancelButtonText: "Batal",
                            reverseButtons: true,
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: "btn fw-semibold btn-light-primary",
                                cancelButton: "btn fw-semibold btn-light-danger",
                            },
                        });

                        if (result.isConfirmed) {
                            try {
                                const response = await axios.put(
                                    `/transactions/${transaction.id}`,
                                    {
                                        bill_id: transaction.bill_id,
                                        nominal: transaction.nominal,
                                        metode: "Manual",
                                        status: "Berhasil",
                                        metode_pembayaran: "Pembayaran melalui tabungan",
                                        catatan: transaction.catatan ?? "",
                                    }
                                );

                                if (response.data.success) {
                                    await Swal.fire({
                                        icon: "success",
                                        title: "Berhasil!",
                                        text: "Pembayaran berhasil dikonfirmasi.",
                                        confirmButtonText: "OK",
                                        buttonsStyling: false,
                                        customClass: {
                                            confirmButton: "btn fw-semibold btn-light-primary",
                                        },
                                    });

                                    // ⬇⬇⬇ Redireksi ke halaman transaction
                                    router.push({ name: "transaction" });
                                } else {
                                    await Swal.fire({
                                        icon: "error",
                                        title: "Gagal!",
                                        text:
                                            response.data.message ||
                                            "Gagal mengonfirmasi pembayaran.",
                                        confirmButtonText: "OK",
                                        buttonsStyling: false,
                                        customClass: {
                                            confirmButton: "btn fw-semibold btn-light-danger",
                                        },
                                    });
                                }
                            } catch (error) {
                                console.error(error);
                                await Swal.fire({
                                    icon: "error",
                                    title: "Kesalahan!",
                                    text: "Terjadi kesalahan saat mengonfirmasi pembayaran.",
                                    confirmButtonText: "OK",
                                    buttonsStyling: false,
                                    customClass: {
                                        confirmButton: "btn fw-semibold btn-light-danger",
                                    },
                                });
                            }
                        }
                    },
                },
                "Konfirmasi"
            );
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
    <Form
        :selected="selected"
        @close="openForm = false"
        v-if="openForm"
        @refresh="refresh"
    />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Daftar Pembayaran Tertunda</h2>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-transactions"
                url="/transactions/pending"
                :columns="columns"
            />
        </div>
    </div>
</template>
