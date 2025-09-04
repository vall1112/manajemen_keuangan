<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const major = ref<any>({});
const formRef = ref();

const formSchema = Yup.object().shape({
    kode: Yup.string().required("Kode jurusan harus diisi"),
    nama_jurusan: Yup.string().required("Nama jurusan harus diisi"),
    keterangan: Yup.string().nullable(),
});

function getEdit() {
    block(document.getElementById("form-major"));
    axios.get(`/master/majors/${props.selected}`)
        .then(({ data }) => {
            major.value = data.major;
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-major"));
        });
}

function submit() {
    const formData = new FormData();
    formData.append("kode", major.value.kode);
    formData.append("nama_jurusan", major.value.nama_jurusan);
    formData.append("keterangan", major.value.keterangan || "");

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-major"));
    axios({
        method: "post",
        url: props.selected
            ? `/master/majors/${props.selected}`
            : "/master/majors/store",
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
            unblock(document.getElementById("form-major"));
        });
}

onMounted(() => {
    if (props.selected) {
        getEdit();
    }
});

watch(() => props.selected, () => {
    if (props.selected) {
        getEdit();
    }
});
</script>

<template>
    <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-major" ref="formRef">
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ props.selected ? "Edit" : "Tambah" }} Jurusan</h2>
            <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
                Batal
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Kode Jurusan</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="kode"
                            v-model="major.kode" placeholder="Masukkan Kode Jurusan" />
                        <ErrorMessage name="kode" class="text-danger" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Nama Jurusan</label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="nama_jurusan"
                            v-model="major.nama_jurusan" placeholder="Masukkan Nama Jurusan" />
                        <ErrorMessage name="nama_jurusan" class="text-danger" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">Keterangan</label>
                        <Field class="form-control form-control-lg form-control-solid" as="textarea" name="keterangan"
                            v-model="major.keterangan" placeholder="Masukkan Keterangan (opsional)" />
                        <ErrorMessage name="keterangan" class="text-danger" />
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
