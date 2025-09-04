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

const schoolYear = ref<any>({});
const formRef = ref();

const formSchema = Yup.object().shape({
    tahun_ajaran: Yup.string().required("Tahun ajaran harus diisi"),
    // semester: Yup.string().required("Semester harus dipilih"),
    status: Yup.string().required("Status harus dipilih"),
});

function getEdit() {
    block(document.getElementById("form-school-year"));
    ApiService.get("master/school-years", props.selected)
        .then(({ data }) => {
            schoolYear.value = data.school_year;
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-school-year"));
        });
}

function submit() {
    const formData = new FormData();
    formData.append("tahun_ajaran", schoolYear.value.tahun_ajaran);
    // formData.append("semester", schoolYear.value.semester);
    formData.append("status", schoolYear.value.status);

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-school-year"));
    axios({
        method: "post",
        url: props.selected
            ? `/master/school-years/${props.selected}`
            : "/master/school-years/store",
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
            unblock(document.getElementById("form-school-year"));
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
        id="form-school-year"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Tahun Ajaran</h2>
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
                <div class="col-md-4">
                    <!-- Tahun Ajaran -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Tahun Ajaran
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="tahun_ajaran"
                            v-model="schoolYear.tahun_ajaran"
                            placeholder="Contoh: 2025/2026"
                        />
                        <ErrorMessage name="tahun_ajaran" class="text-danger small" />
                    </div>
                </div>
                <!-- <div class="col-md-4">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Semester
                        </label>
                        <Field
                            as="select"
                            class="form-select form-select-lg form-select-solid"
                            name="semester"
                            v-model="schoolYear.semester"
                        >
                            <option value="">Pilih Semester</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </Field>
                        <ErrorMessage name="semester" class="text-danger small" />
                    </div>
                </div> -->
                <div class="col-md-4">
                    <!-- Status -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Status
                        </label>
                        <Field
                            as="select"
                            class="form-select form-select-lg form-select-solid"
                            name="status"
                            v-model="schoolYear.status"
                        >
                            <option value="">Pilih Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </Field>
                        <ErrorMessage name="status" class="text-danger small" />
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
