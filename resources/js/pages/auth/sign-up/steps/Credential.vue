<template>
    <section class="w-100">
         <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Username</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="username" autocomplete="off"
                v-model="formData.username" />
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="username" />
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Nama Siswa</label>
            <Field class="form-control form-control-lg form-control-solid" type="text" name="nama" autocomplete="off"
                v-model="formData.nama" />
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="nama" />
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bold fs-6">Email</label>
            <Field class="form-control form-control-lg form-control-solid" type="email" name="email" autocomplete="off"
                v-model="formData.email" />
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="email" />
                </div>
            </div>
        </div>
        <!--end::Input group-->

    </section>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'Credential',
    props: {
        formData: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        return {
            formData: props.formData,
        }
    },
})
</script>
