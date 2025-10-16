<template>
    <VForm class="form w-100" @submit="submit" :validation-schema="schema">
        <!-- Input Kelas -->
        <div class="fv-row mb-10">
            <label class="form-label fs-6 fw-bold">Kelas</label>
            <select
                v-model="selectedClass"
                class="form-select form-select-lg form-select-solid"
            >
                <option disabled value="">Pilih Kelas</option>
                <option
                    v-for="c in classes"
                    :key="c.id"
                    :value="c.id"
                >
                    {{ c.nama_kelas }}
                </option>
            </select>
        </div>

        <!-- Input Nama Siswa -->
        <div class="fv-row mb-10">
            <label class="form-label fs-6 fw-bold">Nama Siswa</label>
            <select
                v-model="data.student_id"
                class="form-select form-select-lg form-select-solid"
                :disabled="!selectedClass"
            >
                <option disabled value="">
                    {{ selectedClass ? 'Pilih Nama Siswa' : 'Pilih Kelas Terlebih Dahulu' }}
                </option>
                <option
                    v-for="s in filteredStudents"
                    :key="s.id"
                    :value="s.id"
                >
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
            <Field
                type="password"
                name="password"
                class="form-control form-control-lg form-control-solid"
                v-model="data.password"
                placeholder="Masukkan password"
                autocomplete="off"
            />
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="password" />
                </div>
            </div>
        </div>

        <!-- Tombol Login -->
        <div class="text-center">
            <button
                ref="submitButton"
                type="submit"
                class="btn btn-lg btn-primary w-100 mb-5"
            >
                <span class="indicator-label">Masuk</span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </VForm>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from "vue";
import axios from "@/libs/axios";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import * as Yup from "yup";
import { toast } from "vue3-toastify";
import { blockBtn, unblockBtn } from "@/libs/utils";
import { Field, ErrorMessage, VForm } from "vee-validate";

export default defineComponent({
    name: "WithStudent",
    components: { Field, ErrorMessage, VForm },
    setup() {
        const store = useAuthStore();
        const router = useRouter();

        const data = ref({
            student_id: "",
            password: "",
        });

        const students = ref<any[]>([]);
        const classes = ref<any[]>([]);
        const selectedClass = ref("");
        const loadingStudents = ref(false);
        const submitButton = ref<HTMLButtonElement | null>(null);

        const schema = Yup.object().shape({
            student_id: Yup.number().required("Pilih nama siswa"),
            password: Yup.string().required("Masukkan password"),
        });

        /** 🔹 Ambil data siswa (berisi juga data kelas) */
        const fetchStudents = async () => {
            try {
                loadingStudents.value = true;
                const res = await axios.get("/login/students");

                // Simpan semua siswa
                students.value = res.data.data || res.data;

                // Dapatkan daftar kelas unik
                const uniqueClasses = new Map();
                students.value.forEach((s: any) => {
                    if (s.classroom) {
                        uniqueClasses.set(s.classroom.id, s.classroom);
                    }
                });

                classes.value = Array.from(uniqueClasses.values());
            } catch (error) {
                console.error(error);
                toast.error("Gagal memuat daftar siswa dan kelas");
            } finally {
                loadingStudents.value = false;
            }
        };

        /** 🔹 Filter siswa berdasarkan kelas yang dipilih */
        const filteredStudents = computed(() => {
            if (!selectedClass.value) return [];
            return students.value.filter(
                (s) => s.classroom_id === selectedClass.value
            );
        });

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
            classes,
            selectedClass,
            filteredStudents,
            loadingStudents,
            submit,
            schema,
            submitButton,
        };
    },
});
</script>
