<template>
    <VForm class="form w-100" @submit="submit" :validation-schema="schema">
        <!-- Input Nama Siswa -->
        <div class="fv-row mb-10">
            <label class="form-label fs-6 fw-bold">Nama Siswa</label>
            <select v-model="data.student_id" class="form-select form-select-lg form-select-solid">
                <option disabled value="">Pilih Nama Siswa</option>
                <option v-for="s in students" :key="s.id" :value="s.id">
                    {{ s.nama }} - {{ s.classroom?.nama_kelas ?? '' }}
                </option>
            </select>
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="student_id" />
                </div>
            </div>
        </div>

        <!-- Input Password -->
        <div class="fv-row mb-10">
            <label class="form-label fs-6 fw-bold">Password</label>
            <Field type="password" name="password" class="form-control form-control-lg form-control-solid"
                v-model="data.password" placeholder="Masukkan password" autocomplete="off" />
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="password" />
                </div>
            </div>
        </div>

        <!-- Tombol Login -->
        <div class="text-center">
            <button ref="submitButton" type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                <span class="indicator-label">Masuk</span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </VForm>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "@/libs/axios";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import * as Yup from "yup";
import { toast } from "vue3-toastify";
import { blockBtn, unblockBtn } from "@/libs/utils";

export default defineComponent({
    name: "WithStudent",
    setup() {
        const store = useAuthStore();
        const router = useRouter();

        const data = ref({
            student_id: "",
            password: "",
        });

        const students = ref<any[]>([]);
        const loadingStudents = ref(false);
        const submitButton = ref<HTMLButtonElement | null>(null);

        const schema = Yup.object().shape({
            student_id: Yup.number().required("Pilih nama siswa"),
            password: Yup.string().required("Masukkan password"),
        });

        /** 🔹 Ambil data siswa dari route /login/students */
        const fetchStudents = async () => {
            try {
                loadingStudents.value = true;
                const res = await axios.get("/login/students");
                students.value = res.data.data || res.data;
            } catch (error) {
                console.error(error);
                toast.error("Gagal memuat daftar siswa");
            } finally {
                loadingStudents.value = false;
            }
        };

        /** 🔹 Aksi submit login */
        const submit = async () => {
            blockBtn(submitButton.value);
            try {
                const res = await axios.post("/auth/login/student", {
                    student_id: data.value.student_id,
                    password: data.value.password,
                    type: "student",
                });

                store.setAuth(res.data.user, res.data.token);
                router.push("/student/dashboard");
            } catch (error: any) {
                toast.error(error.response?.data?.message || "Login gagal");
            } finally {
                unblockBtn(submitButton.value);
            }
        };

        onMounted(fetchStudents);

        return {
            data,
            students,
            loadingStudents,
            submit,
            schema,
            submitButton,
        };
    },
});
</script>
