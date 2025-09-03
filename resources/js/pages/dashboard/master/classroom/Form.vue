<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Teacher, Major } from "@/types"; 
import ApiService from "@/core/services/ApiService";
import { useTeacher } from "@/services/useTeacher";
import { useMajor } from "@/services/useMajor";

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const classroom = ref<any>({});
const formRef = ref();

const formSchema = Yup.object().shape({
    nama_kelas: Yup.string().required("Nama Kelas harus diisi"),
    major_id: Yup.string().required("Pilih jurusan"),
    wali_kelas_id: Yup.string().required("Pilih wali kelas"),
    jumlah_anak: Yup.number()
        .typeError("Jumlah anak harus berupa angka")
        .required("Jumlah anak harus diisi"),
});

function getEdit() {
    block(document.getElementById("form-classroom"));
    ApiService.get("master/classrooms", props.selected)
        .then(({ data }) => {
            classroom.value = data.classroom;
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-classroom"));
        });
}

function submit() {
    const formData = new FormData();
    formData.append("nama_kelas", classroom.value.nama_kelas);
    formData.append("major_id", classroom.value.major_id);
    formData.append("wali_kelas_id", classroom.value.wali_kelas_id);
    formData.append("jumlah_anak", classroom.value.jumlah_anak);

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-classroom"));
    axios({
        method: "post",
        url: props.selected
            ? `/master/classrooms/${props.selected}`
            : "/master/classrooms/store",
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
            unblock(document.getElementById("form-classroom"));
        });
}

// ambil data guru (wali kelas)
const teacher = useTeacher();
const teachers = computed(() =>
    teacher.data.value?.map((item: Teacher) => ({
        id: item.id,
        text: item.nama,
    }))
);

const major = useMajor();
const majors = computed(() =>
    major.data.value?.map((item: Major) => ({
        id: item.id,
        text: item.nama_jurusan,
    }))
);

onMounted(async () => {
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
        id="form-classroom"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Kelas</h2>
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
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Nama Kelas
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="nama_kelas"
                            v-model="classroom.nama_kelas"
                            placeholder="Masukkan Nama Kelas"
                        />
                        <ErrorMessage name="nama_kelas" class="text-danger" />
                    </div>
                </div>
               <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Jurusan
                        </label>
                        <Field name="major_id" type="hidden" v-model="classroom.major_id">
                            <select2 placeholder="Pilih jurusan" class="form-select-solid" :options="majors" name="major_id"
                                v-model="classroom.major_id">
                            </select2>
                        </Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="major_id" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Wali Kelas
                        </label>
                        <Field name="wali_kelas_id" type="hidden" v-model="classroom.wali_kelas_id">
                            <select2
                                placeholder="Pilih Wali Kelas"
                                class="form-select-solid"
                                :options="teachers"
                                name="wali_kelas_id"
                                v-model="classroom.wali_kelas_id"
                            >
                            </select2>
                        </Field>
                        <ErrorMessage name="wali_kelas_id" class="text-danger" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Jumlah Anak
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="number"
                            name="jumlah_anak"
                            v-model="classroom.jumlah_anak"
                            placeholder="Masukkan Jumlah Anak"
                        />
                        <ErrorMessage name="jumlah_anak" class="text-danger" />
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
