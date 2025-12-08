<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Bill } from "@/types";
import { useRouter } from "vue-router";

const router = useRouter();
const column = createColumnHelper<Bill>();
const paginateRef = ref<any>(null);
const selected = ref<number | null>(null);
const openForm = ref<boolean>(false);

// Modal WhatsApp State
const showWAModal = ref<boolean>(false);
const waMessage = ref<string>("");
const waPhone = ref<string>("");

const { delete: deleteBill } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const formatUang = (value: number) => {
    return "Rp " + new Intl.NumberFormat("id-ID").format(value ?? 0);
};

const formatTanggal = (value: string | null) => {
    if (!value) return "-";
    return new Date(value).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const formatNomorWA = (nomor: string | null) => {
    if (!nomor) return null;
    if (nomor.startsWith("0")) return "+62" + nomor.substring(1);
    return nomor;
};

const openWAModal = (row: any) => {
    const namaSiswa = row.student?.nama ?? "-";
    const kelas = row.student?.classroom?.nama_kelas ?? "-";
    const jenis = row.payment_type?.nama_jenis ?? "-";
    const total = formatUang(row.total_tagihan);
    const dueDate = formatTanggal(row.jatuh_tempo);
    const nomorWA = formatNomorWA(row.student?.telepon ?? null);

    waPhone.value = nomorWA ?? "";
    waMessage.value =
        `Halo, ini pemberitahuan mengenai tagihan sekolah.\n\n` +
        `Nama Siswa : ${namaSiswa}\n` +
        `Kelas : ${kelas}\n` +
        `Jenis Pembayaran : ${jenis}\n` +
        `Total Tagihan : ${total}\n` +
        `Jatuh Tempo : ${dueDate}\n\n` +
        `Mohon segera melakukan pembayaran sebelum jatuh tempo.\n` +
        `Terima kasih.`;

    showWAModal.value = true;
};

const sendWA = () => {
    const url = `https://wa.me/${waPhone.value}?text=${encodeURIComponent(waMessage.value)}`;
    window.open(url, "_blank");
    showWAModal.value = false;
};

const columns = [
    column.accessor("no", { header: "#" }),
    column.accessor("kode", { header: "Kode" }),
    column.accessor("student_id", {
        header: "Nama",
        cell: (info) => info.row.original.student?.nama ?? "-",
    }),
    column.accessor("payment_type_id", {
        header: "Jenis Pembayaran",
        cell: (info) => info.row.original.payment_type?.nama_jenis ?? "-",
    }),
    column.accessor("school_year_id", {
        header: "Tahun Ajaran",
        cell: (info) => info.row.original.school_year?.tahun_ajaran ?? "-",
    }),
    column.accessor("total_tagihan", {
        header: "Total Tagihan",
        cell: (info) => formatUang(info.getValue()),
    }),
    column.accessor("tanggal_tagih", {
        header: "Tanggal Tagih",
        cell: (info) => formatTanggal(info.getValue()),
    }),
    column.accessor("jatuh_tempo", {
        header: "Jatuh Tempo",
        cell: (info) => formatTanggal(info.getValue()),
    }),
    column.accessor("status", {
        header: "Status",
        cell: (info) => {
            const status = info.getValue();
            const cls =
                status === "Lunas"
                    ? "text-success"
                    : status === "Pending"
                        ? "text-warning"
                        : status === "Belum Dibayar"
                            ? "text-danger"
                            : "text-muted";
            return h("span", { class: `fw-semibold ${cls}` }, status);
        },
    }),
    column.accessor("id", {
        header: "Aksi",
        cell: (cell) => {
            const row = cell.row.original;
            const kode = row.kode;

            const buttons: any[] = [];

            if (row.status === "Belum Dibayar") {
                buttons.push(
                    h(
                        "button",
                        {
                            class: "btn btn-sm btn-light-success d-flex align-items-center",
                            title: "Bayar Tagihan",
                            onClick: () => {
                                router.push({
                                    name: "form.transaction",
                                    query: { kode },
                                });
                            },
                        },
                        [h("i", { class: "la la-credit-card fs-2" }), h("span", " Bayar")]
                    )
                );

                if (row.student?.telepon) {
                    buttons.push(
                        h(
                            "button",
                            {
                                class: "btn btn-sm btn-light-primary d-flex align-items-center",
                                title: "Kirim WhatsApp",
                                onClick: () => openWAModal(row),
                            },
                            [
                                h("i", { class: "lab la-whatsapp fs-2 text-success" }),
                                h("span", " Chat Wa"),
                            ]
                        )
                    );
                }
            }

            if (buttons.length === 0) {
                return h("span", { class: "text-muted fst-italic" }, "-");
            }

            return h("div", { class: "d-flex gap-2" }, buttons);
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

    <!-- Background blur -->
    <div v-if="showWAModal" class="modal-backdrop-blur"></div>

    <!-- Modal tepat di tengah -->
    <div v-if="showWAModal" class="modal fade show modal-centered">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pesan WhatsApp</h5>
                    <button type="button" class="btn-close" @click="showWAModal = false"></button>
                </div>

                <div class="modal-body">
                    <textarea v-model="waMessage" rows="8" class="form-control"></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="showWAModal = false">Batal</button>
                    <button class="btn btn-success" @click="sendWA">Kirim WhatsApp</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Daftar Tagihan</h2>
            <button type="button" class="btn btn-sm btn-primary ms-auto" v-if="!openForm"
                @click="openForm = true">
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>

        <div class="card-body">
            <paginate ref="paginateRef" id="table-bills" url="/bills" :columns="columns" />
        </div>
    </div>
</template>

<style scoped>
.modal-backdrop-blur {
    position: fixed;
    inset: 0;
    backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.2);
    z-index: 1050;
}

.modal-centered {
    display: flex !important;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    z-index: 1051;
}
</style>
