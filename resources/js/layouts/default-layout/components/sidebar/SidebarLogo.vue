<script setup lang="ts">
import { onMounted, ref } from "vue";
import { ToggleComponent } from "@/assets/ts/components";
import { getAssetPath } from "@/core/helpers/assets";
import {
    layout,
    sidebarToggleDisplay,
    themeMode,
} from "@/layouts/default-layout/config/helper";
import { useSetting } from "@/services";

interface IProps {
    sidebarRef: HTMLElement | null;
}

const props = defineProps<IProps>();

const { data: setting = {} } = useSetting()

const toggleRef = ref<HTMLFormElement | null>(null);

onMounted(() => {
    setTimeout(() => {
        const toggleObj = ToggleComponent.getInstance(
            toggleRef.value!
        ) as ToggleComponent | null;

        if (toggleObj === null) return;

        toggleObj.on("kt.toggle.change", function () {
            props.sidebarRef?.classList.add("animating");
            setTimeout(function () {
                props.sidebarRef?.classList.remove("animating");
            }, 300);
        });
    }, 1);
});
</script>

<template>
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6 text-center" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <router-link to="/">
            <img 
                v-if="layout === 'dark-sidebar' || (themeMode === 'dark' && layout === 'light-sidebar')" 
                :src="setting?.logo_sekolah" 
                alt="Logo Sekolah" 
                class="h-50px app-sidebar-logo-default mb-2" 
            />
            <img 
                v-if="themeMode === 'light' && layout === 'light-sidebar'" 
                :src="setting?.logo_sekolah" 
                alt="Logo Sekolah" 
                class="h-50px app-sidebar-logo-default mb-2" 
            />
            <img 
                :src="setting?.logo_sekolah" 
                alt="Logo Sekolah" 
                class="h-50px app-sidebar-logo-minimize mb-2" 
            />
        </router-link>
        <!--end::Logo image-->

        <!--begin::Nama Sekolah-->
        <div class="fw-bold text-white text-center fs-6">
            {{ setting?.school || 'Nama Sekolah' }}
        </div>
        <!--end::Nama Sekolah-->

        <!--begin::Sidebar toggle-->
        <div 
            v-if="sidebarToggleDisplay"
            ref="toggleRef"
            id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary 
                   h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true"
            data-kt-toggle-state="active"
            data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize"
        >
            <KTIcon icon-name="black-left-line" icon-class="fs-3 rotate-180 ms-1" />
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
</template>

<style scoped>
.app-sidebar-logo img {
    object-fit: contain;
}
</style>
