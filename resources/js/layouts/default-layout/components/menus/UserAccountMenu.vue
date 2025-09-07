<script setup lang="ts">
import { getAssetPath } from "@/core/helpers/assets";
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import Swal from "sweetalert2/dist/sweetalert2.js";

// Initialize stores and router
const router = useRouter();
const i18n = useI18n();
const authStore = useAuthStore();

// Set initial language
i18n.locale.value = localStorage.getItem("lang") ?? "en";

// Language configurations
const countries = {
    en: { flag: getAssetPath("media/flags/united-states.svg"), name: "English" },
    es: { flag: getAssetPath("media/flags/spain.svg"), name: "Spanish" },
    de: { flag: getAssetPath("media/flags/germany.svg"), name: "German" },
    ja: { flag: getAssetPath("media/flags/japan.svg"), name: "Japanese" },
    fr: { flag: getAssetPath("media/flags/france.svg"), name: "French" },
};

// Sign out function
const signOut = async () => {
    const result = await Swal.fire({
        icon: "warning",
        text: "Apakah Anda yakin ingin keluar?",
        showCancelButton: true,
        confirmButtonText: "Ya, Keluar",
        cancelButtonText: "Batal",
        reverseButtons: true,
        buttonsStyling: false,
        customClass: {
            confirmButton: "btn fw-semibold btn-light-primary",
            cancelButton: "btn fw-semibold btn-light-danger",
        },
    });

    if (result.isConfirmed) {
        authStore.logout();
        await Swal.fire({
            icon: "success",
            text: "Berhasil keluar",
        });
        router.push({ name: "sikaz" });
    }
};

// Language handling
const setLang = (lang: string) => {
    localStorage.setItem("lang", lang);
    i18n.locale.value = lang;
};

const currentLanguage = computed(() => i18n.locale.value);
const currentLanguageLocale = computed(() => countries[i18n.locale.value as keyof typeof countries] || countries.en);

// Student or User photo handling
const userPhoto = computed(() => {
  if (authStore.user?.student_id && authStore.user?.student?.foto) {
    // Foto dari student jika user punya student_id
    return `/storage/${authStore.user.student.foto}`;
  } else if (authStore.user?.photo) {
    // Foto dari user jika tidak punya student_id
    return `/storage/${authStore.user.photo}`;
  }
  // Default
  return "/media/avatars/blank.png";
});
</script>

<template>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold py-4 fs-6 w-275px"
        data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <div class="symbol symbol-50px me-5">
                    <img :src="userPhoto" alt="Student Photo" />
                </div>
                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">
                        {{ authStore.user?.name ?? 'Guest' }}
                        <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                            <!-- jika punya student -->
                            <template v-if="authStore.user?.student">
                                {{ authStore.user?.student?.classroom?.nama_kelas ?? 'N/A' }}
                            </template>
                            <!-- jika tidak punya student -->
                            <template v-else>
                                {{ authStore.user?.role?.name ?? 'N/A' }}
                            </template>
                        </span>
                    </div>

                    <!-- jika punya student -->
                    <a v-if="authStore.user?.student" href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                        {{ authStore.user?.student?.nama ?? 'N/A' }}
                    </a>

                    <!-- jika tidak punya student -->
                    <a v-else href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                        {{ authStore.user?.email ?? 'N/A' }}
                    </a>
                </div>
            </div>
        </div>
        <div class="separator my-2"></div>
        <div class="menu-item px-5 my-1">
            <router-link v-if="authStore.user?.student_id" to="/dashboard/profile/student" class="menu-link px-5">
                Account Settings
            </router-link>

            <router-link v-else to="/dashboard/profile" class="menu-link px-5">
                Account Settings
            </router-link>
        </div>

        <div class="menu-item px-5">
            <a @click="signOut" class="menu-link px-5">Sign Out</a>
        </div>
    </div>
</template>
