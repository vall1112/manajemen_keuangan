<template>
    <section class="w-100">
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bold text-dark fs-6">Email</label>
            <Field 
                class="form-control form-control-lg form-control-solid" 
                type="text" 
                name="email" 
                autocomplete="off"
                v-model="formData.email" 
                disabled 
            />
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="email" />
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bold text-dark fs-6">Kode OTP</label>
            
            <!-- Manual Input Mode -->
            <Field 
                class="form-control form-control-lg form-control-solid" 
                type="text" 
                name="otp_email" 
                autocomplete="off"
                v-model="formData.otp_email" 
                placeholder="Masukkan kode OTP"
                maxlength="6"
                @input="handleManualInput" 
            />
            
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <ErrorMessage name="otp_email" />
                </div>
            </div>
            
            <div v-if="otpInterval == 0" class="text-gray-400 fw-semibold fs-4 text-end mt-4">
                Tidak menerima kode OTP?
                <button type="button" class="btn p-0 link-primary fw-bold" @click="sendOtp">
                    Kirim Ulang
                </button>
            </div>
            <div v-else class="text-gray-400 fw-semibold fs-4 text-end mt-4">
                Kode OTP dapat dikirim ulang dalam <span class="fw-bold">{{ otpInterval }}</span> detik
            </div>
        </div>
        <!--end::Input group-->
    </section>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { Field, ErrorMessage } from 'vee-validate';
import { useOtpIntervalStore } from '../Index.vue'; 

export default defineComponent({
    name: 'VerifyEmail',
    components: {
        Field,
        ErrorMessage
    },
    props: {
        formData: {
            type: Object,
            required: true,
        },
    },
    emits: ['onComplete', 'sendOtp'],
    setup(props, ctx) {
        const { otpInterval } = useOtpIntervalStore();

        const handleManualInput = (event: Event) => {
            const target = event.target as HTMLInputElement;
            let value = target.value;
            
            if (!/^\d*$/.test(value)) {
                value = value.replace(/\D/g, '');
                props.formData.otp_email = value;
                target.value = value;
            }
            
            if (value.length === 6) {
                ctx.emit('onComplete', value);
            }
        }

        const sendOtp = () => {
            ctx.emit('sendOtp')
        }

        return {
            formData: props.formData,
            handleManualInput,
            sendOtp,
            otpInterval,
        }
    },
})
</script>