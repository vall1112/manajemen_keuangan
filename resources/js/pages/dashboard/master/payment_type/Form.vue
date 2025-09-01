<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import ApiService from "@/core/services/ApiService";

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const paymentType = ref<any>({});
const formRef = ref();

const formSchema = Yup.object().shape({
    nama_jenis: Yup.string().required("Jenis Pembayaran harus diisi"),
    keterangan: Yup.string().nullable(),
});

function getEdit() {
    block(document.getElementById("form-payment-type"));
    ApiService.get("master/payment-types", props.selected)
        .then(({ data }) => {
            paymentType.value = data.payment_type;
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-payment-type"));
        });
}

function submit() {
    const formData = new FormData();
    formData.append("nama_jenis", paymentType.value.nama_jenis);
    formData.append("keterangan", paymentType.value.keterangan ?? "");

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-payment-type"));
    axios({
        method: "post",
        url: props.selected
            ? `/master/payment-types/${props.selected}`
            : "/master/payment-types/store",
        data: formData,
    })
        .then(() => {
            emit("close");
            emit("refresh");
            toast.success("Data berhasil disimpan");
            formRef.value.resetForm();
        })
        .catch((err: any) => {
            formRef.value.setErrors(err.response.data.errors);
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-payment-type"));
        });
}

onMounted(() => {
    if (props.selected) {
        getEdit();
    }
});

watch(
    () => props.selected,
    () => {
        if (props.selected) {
            getEdit();
        }
    }
);
</script>

<template>
    <VForm
        class="form card mb-10"
        @submit="submit"
        :validation-schema="formSchema"
        id="form-payment-type"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Jenis Pembayaran</h2>
            <button
                type="button"
                class="btn btn-sm btn-light-danger ms-auto"
                @click="emit('close')"
            >
                Batal
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- Nama Jenis -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Jenis Pembayaran
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="nama_jenis"
                            autocomplete="off"
                            v-model="paymentType.nama_jenis"
                            placeholder="Masukkan nama jenis pembayaran"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="nama_jenis" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Keterangan -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">
                            Keterangan
                        </label>
                        <Field
                            as="textarea"
                            class="form-control form-control-lg form-control-solid"
                            name="keterangan"
                            v-model="paymentType.keterangan"
                            placeholder="Masukkan keterangan (opsional)"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="keterangan" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
                Simpan
            </button>
        </div>
    </VForm>
</template>
