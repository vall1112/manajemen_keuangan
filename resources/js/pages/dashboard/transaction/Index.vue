    <script setup lang="ts">
    import { h, ref, watch } from "vue";
    import { useDelete } from "@/libs/hooks";
    import Form from "./Form.vue";
    import { createColumnHelper } from "@tanstack/vue-table";
    import type { Transaction } from "@/types";

    const column = createColumnHelper<Transaction>();
    const paginateRef = ref<any>(null);
    const selected = ref<number | null>(null);
    const openForm = ref<boolean>(false);

    const { delete: deleteTransaction } = useDelete({
        onSuccess: () => paginateRef.value.refetch(),
    });

    const printReceipt = async (transactionId: number) => {
        try {
            const url = `/api/transactions/${transactionId}/receipt`;

            // Ambil HTML struk dari backend
            const response = await fetch(url);
            const receiptHtml = await response.text();

            // Buat popup window sementara
            const printWindow = window.open("", "PRINT", "width=900,height=650");

            if (printWindow) {
                printWindow.document.write(receiptHtml);
                printWindow.document.close();

                // Tunggu render, lalu panggil print
                printWindow.focus();
                printWindow.print();
                // opsional: auto-close setelah print
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
        // column.accessor("kode_tagihan", { header: "Kode Tagihan" }),
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
        column.accessor("status", {
            header: "Status",
            cell: (info) => {
                const value = info.getValue() ?? "-";
                return h("span", {
                    class: value === "Berhasil" ? "text-success" : "text-danger",
                }, value);
            }
        }),
        column.accessor("id", {
            header: "Aksi",
            cell: (info) => {
                const transaction = info.row.original;
                return h("button", {
                    class: "btn btn-sm btn-info",
                    onClick: () => printReceipt(transaction.id),
                }, "Cetak Struk");
            }
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
                <h2 class="mb-0">Daftar Transaksi Tagihan</h2>
            </div>
            <div class="card-body">
                <paginate ref="paginateRef" id="table-transactions" url="/transactions" :columns="columns"></paginate>
            </div>
        </div>
    </template>
