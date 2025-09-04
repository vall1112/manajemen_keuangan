<script setup lang="ts">
import { getAssetPath } from "@/core/helpers/assets";
import { computed, ref, onMounted, onUnmounted } from "vue";
import KTUserMenu from "@/layouts/default-layout/components/menus/UserAccountMenu.vue";
import KTThemeModeSwitcher from "@/layouts/default-layout/components/theme-mode/ThemeModeSwitcher.vue";
import { ThemeModeComponent } from "@/assets/ts/layout";
import { useThemeStore } from "@/stores/theme";
import { useAuthStore } from "@/stores/auth";
import { useTahunStore } from "@/stores/tahun";

const themeStore = useThemeStore();
const authStore = useAuthStore();
const tahunStore = useTahunStore();

const themeMode = computed(() => {
    return themeStore.mode === "system"
        ? ThemeModeComponent.getSystemMode()
        : themeStore.mode;
});

const tahuns = ref<number[]>([]);
const currentYear = new Date().getFullYear();
for (let i = currentYear; i >= currentYear - 2; i--) {
    tahuns.value.push(i);
}

// Student photo handling
const studentFoto = computed(() => {
    if (authStore.user?.student?.foto) {
        return `/storage/${authStore.user.student.foto}`;
    }
    return "/media/avatars/blank.png";
});

// Logika untuk waktu dan tanggal
const currentTime = ref<string>("");
const currentDate = ref<string>("");

const updateTimeAndDate = () => {
    const now = new Date();

    // Format waktu: HH:mm:ss
    const time = now.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });

    // Format tanggal: Hari, DD MMMM YYYY
    const date = now.toLocaleDateString("id-ID", {
        weekday: "long",
        day: "2-digit",
        month: "long",
        year: "numeric",
    });

    currentTime.value = time;
    currentDate.value = date;
};

// Perbarui waktu setiap detik
let intervalId: number | null = null;
onMounted(() => {
    updateTimeAndDate(); // Panggil sekali saat komponen dimuat
    intervalId = setInterval(updateTimeAndDate, 1000); // Perbarui setiap detik
});

// Bersihkan interval saat komponen di-unmount
onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId);
    }
});
</script>

<template>
    <!--begin::Navbar-->
    <div class="app-navbar flex-shrink-0">
        <!--begin::Date-->
        <div class="app-navbar-item me-10">
            <div class="d-flex flex-column align-items-start">
                <span class="fs-6">{{ currentDate }}</span>
            </div>
        </div>
        <!--end::Date-->

        <!--begin::Time-->
        <div class="app-navbar-item me-10">
            <div class="d-flex flex-column align-items-start">
                <span class="fs-5">{{ currentTime }}</span>
            </div>
        </div>
        <!--end::Time-->

        <!--begin::Year selector-->
        <!-- <div class="app-navbar-item me-10">
            <select2 
                class="form-select-solid w-125px" 
                :options="tahuns" 
                v-model="tahunStore.tahun"
            ></select2>
        </div> -->
        <!--end::Year selector-->

        <!--begin::Theme mode-->
        <div class="app-navbar-item ms-1 ms-md-3">
            <!--begin::Menu toggle-->
            <a href="#"
                class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
                data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
                data-kt-menu-placement="bottom-end">
                <KTIcon :icon-name="themeMode === 'light' ? 'night-day' : 'moon'" icon-class="fs-2" />
            </a>
            <!--end::Menu toggle-->
            <KTThemeModeSwitcher />
        </div>
        <!--end::Theme mode-->

        <!--begin::User menu-->
        <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
            <!--begin::Menu wrapper-->
            <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                <img :src="studentFoto" class="rounded-3" alt="Student Foto" />
            </div>
            <KTUserMenu />
            <!--end::Menu wrapper-->
        </div>
        <!--end::User menu-->

        <!--begin::Header menu toggle-->
        <div class="app-navbar-item d-lg-none ms-2 me-n2" v-tooltip="'Show header menu'">
            <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
                <KTIcon icon-name="element-4" icon-class="fs-2" />
            </div>
        </div>
        <!--end::Header menu toggle-->
    </div>
    <!--end::Navbar-->
</template>