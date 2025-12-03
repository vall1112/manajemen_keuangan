import {
    createRouter,
    createWebHistory,
    type RouteRecordRaw,
} from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useConfigStore } from "@/stores/config";
import NProgress from "nprogress";
import "nprogress/nprogress.css";

declare module "vue-router" {
    interface RouteMeta {
        pageTitle?: string;
        permission?: string;
    }
}

const routes: Array<RouteRecordRaw> = [
    // HALAMAN AWAL SAAT MENGAKSES URL
    {
        path: "/",
        redirect: "/simaku",
        component: () => import("@/layouts/default-layout/DefaultLayout.vue"),
        meta: {
            middleware: "auth",
        },
        children: [
            // HALAMAN DASHBOARD ADMIN
            {
                path: "/dashboard",
                name: "dashboard",
                component: () => import("@/pages/dashboard/Admin.vue"),
                meta: {
                    pageTitle: "Dashboard",
                    breadcrumbs: ["Dashboard"],
                },
            },
            // HALAMAN DASHBOARD BENDAHARA
            {
                path: "/bendahara/dashboard/",
                name: "bendahara.dashboard",
                component: () => import("@/pages/dashboard/Bendahara.vue"),
                meta: {
                    pageTitle: "Dashboard",
                    breadcrumbs: ["Dashboard"],
                },
            },
            // HALAMAN DASHBOARD SISWA
            {
                path: "/student/dashboard",
                name: "student.dashboard",
                component: () => import("@/pages/dashboard/Siswa.vue"),
                meta: {
                    pageTitle: "Dashboard",
                    breadcrumbs: ["Dashboard"],
                },
            },
            // HALAMAN PENGGUNA ADMIN
            {
                path: "/dashboard/master/users/admin",
                name: "dashboard.master.users.admin",
                component: () =>
                    import("@/pages/dashboard/master/users/admin/Index.vue"),
                meta: {
                    pageTitle: "Admin",
                    breadcrumbs: ["Master", "Users", "Admin"],
                },
            },
            // HALAMAN PENGGUNA GURU
            {
                path: "/dashboard/master/users/teacher",
                name: "dashboard.master.users.teacher",
                component: () =>
                    import("@/pages/dashboard/master/users/teacher/Index.vue"),
                meta: {
                    pageTitle: "Guru",
                    breadcrumbs: ["Master", "Users", "Guru"],
                },
            },
            // HALAMAN PENGGUNA SISWA
            {
                path: "/dashboard/master/users",
                name: "dashboard.master.users",
                component: () =>
                    import("@/pages/dashboard/master/users/Index.vue"),
                meta: {
                    pageTitle: "Pengguna",
                    breadcrumbs: ["Master", "Pengguna"],
                },
            },
            // HALAMAN PERAN
            {
                path: "/dashboard/master/roles",
                name: "dashboard.master.roles",
                component: () =>
                    import("@/pages/dashboard/master/roles/Index.vue"),
                meta: {
                    pageTitle: "Role",
                    breadcrumbs: ["Master", "Role"],
                },
            },
            // HALAMAN GURU
            {
                path: "/dashboard/master/teachers",
                name: "/dashboard.master.teachers",
                component: () =>
                    import("@/pages/dashboard/master/teacher/Index.vue"),
                meta: {
                    pageTitle: "Guru",
                    breadcrumbs: ["Master", "Guru"],
                },
            },
            // HALAMAN SISWA
            {
                path: "/dashboard/master/students",
                name: "/dashboard.master.students",
                component: () =>
                    import("@/pages/dashboard/master/student/Index.vue"),
                meta: {
                    pageTitle: "Siswa",
                    breadcrumbs: ["Master", "Siswa"],
                },
            },
            // HALAMAN KELAS
            {
                path: "/dashboard/master/classrooms",
                name: "/dashboard.master.classrooms",
                component: () =>
                    import("@/pages/dashboard/master/classroom/Index.vue"),
                meta: {
                    pageTitle: "Kelas",
                    breadcrumbs: ["Master", "Kelas"],
                },
            },
            // HALAMAN JURUSAN
            {
                path: "/dashboard/master/majors",
                name: "/dashboard.master.majors",
                component: () =>
                    import("@/pages/dashboard/master/major/Index.vue"),
                meta: {
                    pageTitle: "Jurusan",
                    breadcrumbs: ["Master", "Jurusan"],
                },
            },
            // HALAMAN TAHUN AJARAN
            {
                path: "/dashboard/master/school_years",
                name: "/dashboard.master.school_years",
                component: () =>
                    import("@/pages/dashboard/master/school_year/Index.vue"),
                meta: {
                    pageTitle: "Tahun Ajaran",
                    breadcrumbs: ["Master", "Tahun Ajaran"],
                },
            },
            // HALAMAN JENIS PEMBAYARAN
            {
                path: "/dashboard/master/payment_types",
                name: "/dashboard.master.payment_types",
                component: () =>
                    import("@/pages/dashboard/master/payment_type/Index.vue"),
                meta: {
                    pageTitle: "Jenis Pembayaran",
                    breadcrumbs: ["Master", "Jenis Pembayaran"],
                },
            },
            // HALAMAN TAGIHAN
            {
                path: "/bill",
                name: "bill",
                component: () => import("@/pages/dashboard/bill/Index.vue"),
                meta: {
                    pageTitle: "Tagihan",
                    breadcrumbs: ["Tagihan"],
                },
            },
            // HALAMAN TRANSAKSI TAGIHAN
            {
                path: "/transaction",
                name: "transaction",
                component: () => import("@/pages/dashboard/transaction/Index.vue"),
                meta: {
                    pageTitle: "Transaksi",
                    breadcrumbs: ["Transaksi"],
                },
            },
            // HALAMAN FORM TRANSAKSI TAGIHAN
            {
                path: "/form/transaction",
                name: "form.transaction",
                component: () => import("@/pages/dashboard/transaction/Form.vue"),
                meta: {
                    pageTitle: "Transaksi",
                    breadcrumbs: ["Transaksi"],
                },
            },
            // HALAMAN VERIFIKASI PEMBAYARAN
            {
                path: "/payment/confirmation",
                name: "payment.confirmation",
                component: () => import("@/pages/dashboard/payment-confirmation/Index.vue"),
                meta: {
                    pageTitle: "Konfirmasi Pembayaran",
                    breadcrumbs: ["Konfirmasi Pembayaran"],
                },
            },
            // HALAMAN SETOR TABUNGAN
            {
                path: "/savings/deposit",
                name: "savings.deposit",
                component: () => import("@/pages/dashboard/savings/deposit/Form.vue"),
                meta: {
                    pageTitle: "Setor",
                    breadcrumbs: ["Tabungan", "Setor"],
                },
            },
            // HALAMAN TARIK TABUNGAN
            {
                path: "/savings/pull",
                name: "savings.pull",
                component: () => import("@/pages/dashboard/savings/pull/Form.vue"),
                meta: {
                    pageTitle: "Tarik",
                    breadcrumbs: ["Tabungan", "Tarik"],
                },
            },
            // HALAMAN HISTORI TABUNGAN
            {
                path: "/savings/history",
                name: "savings.history",
                component: () => import("@/pages/dashboard/savings/history/Index.vue"),
                meta: {
                    pageTitle: "Riwayat",
                    breadcrumbs: ["Tabungan", "Riwayat"],
                },
            },
            // HALAMAN SALDO TABUNGAN
            {
                path: "/savings/balance",
                name: "savings.balance",
                component: () => import("@/pages/dashboard/savings/balance/Index.vue"),
                meta: {
                    pageTitle: "Saldo",
                    breadcrumbs: ["Tabungan", "Saldo"],
                },
            },
            // HALAMAN PERSETUJUAN USER BARU
            {
                path: "/dashboard/user-agreement",
                name: "dashboard.user-agreement",
                component: () =>
                    import("@/pages/dashboard/user_agreement/Index.vue"),
                meta: {
                    pageTitle: "Persetujuan Users",
                    breadcrumbs: ["Persetujuan Users"],
                },
            },
            // HALAMAN PENGATURAN
            {
                path: "/dashboard/setting",
                name: "dashboard.setting",
                component: () => import("@/pages/dashboard/setting/Index.vue"),
                meta: {
                    pageTitle: "Website Setting",
                    breadcrumbs: ["Website", "Setting"],
                },
            },
            // HALAMAN TAGIHAN SISWA
            {
                path: "/student/bill",
                name: "student.bill",
                component: () => import("@/pages/student/bill/Index.vue"),
                meta: {
                    pageTitle: "Tagihan",
                    breadcrumbs: ["Tagihan"],
                },
            },
            // HALAMAN TRANSAKSI TAGIHAN SISWA
            {
                path: "/form/student/transaction",
                name: "form.student.transaction",
                component: () => import("@/pages/student/transaction/Form.vue"),
                meta: {
                    pageTitle: "Transaksi",
                    breadcrumbs: ["Transaksi"],
                },
            },
            // HALAMAN TABUNGAN SISWA
            {
                path: "/student/savings",
                name: "student.savings",
                component: () => import("@/pages/student/savings/Index.vue"),
                meta: {
                    pageTitle: "Tabungan",
                    breadcrumbs: ["Tabungan"],
                },
            },

            // HALAMAN DETAIL TABUNGAN PER SISWA
            {
                path: "/savings/detail/:id",
                name: "savings.detail",
                component: () => import("@/pages/dashboard/savings/balance/Detail.vue"),
                props: true,
                meta: {
                    pageTitle: "Detail Tabungan",
                    breadcrumbs: ["Detail", "Tabungan"],
                },
            },
            // HALAMAN PROFIL SELAIN SISWA
            {
                path: "/dashboard/profile",
                name: "dashboard.profile",
                component: () => import("@/pages/dashboard/profile/Index.vue"),
                meta: {
                    pageTitle: "Profile",
                    breadcrumbs: ["Profile"],
                },
            },
            // HALAMAN PROFIL SISWA
            {
                path: "/dashboard/profile/student",
                name: "dashboard.profile/student",
                component: () => import("@/pages/dashboard/profile/Student.vue"),
                meta: {
                    pageTitle: "Profile",
                    breadcrumbs: ["Profile"],
                },
            },
        ],
    },
    // LANDING PAGE
    {
        path: "/",
        children: [
            {
                path: "/simaku",
                name: "simaku",
                component: () => import("@/pages/auth/landing-page/Index.vue"),
                meta: {
                    pageTitle: "SIMAKU",
                    middleware: "guest",
                },
            },
        ],
    },
    // LOGIN
    {
        path: "/",
        component: () => import("@/layouts/AuthLayout.vue"),
        children: [
            {
                path: "/sign-in",
                name: "sign-in",
                component: () => import("@/pages/auth/sign-in/Index.vue"),
                meta: {
                    pageTitle: "Sign In",
                    middleware: "guest",
                },
            },
        ],
    },
    // REGISTER
    {
        path: "/",
        component: () => import("@/layouts/AuthLayout.vue"),
        children: [
            {
                path: "/sign-up",
                name: "sign-up",
                component: () => import("@/pages/auth/sign-up/Index.vue"),
                meta: {
                    pageTitle: "Sign Up",
                    middleware: "guest",
                },
            },
        ],
    },
    // ERROR
    {
        path: "/",
        component: () => import("@/layouts/SystemLayout.vue"),
        children: [
            {
                // the 404 route, when none of the above matches
                path: "/404",
                name: "404",
                component: () => import("@/pages/errors/Error404.vue"),
                meta: {
                    pageTitle: "Error 404",
                },
            },
            {
                path: "/500",
                name: "500",
                component: () => import("@/pages/errors/Error500.vue"),
                meta: {
                    pageTitle: "Error 500",
                },
            },
        ],
    },
    {
        path: "/:pathMatch(.*)*",
        redirect: "/404",
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior(to) {
        // If the route has a hash, scroll to the section with the specified ID; otherwise, scroll to the top of the page.
        if (to.hash) {
            return {
                el: to.hash,
                top: 80,
                behavior: "smooth",
            };
        } else {
            return {
                top: 0,
                left: 0,
                behavior: "smooth",
            };
        }
    },
});

router.beforeEach(async (to, from, next) => {
    if (to.name) {
        // Start the route progress bar.
        NProgress.start();
    }

    const authStore = useAuthStore();
    const configStore = useConfigStore();

    // current page view title
    if (to.meta.pageTitle) {
        document.title = `${to.meta.pageTitle} - ${import.meta.env.VITE_APP_NAME
            }`;
    } else {
        document.title = import.meta.env.VITE_APP_NAME as string;
    }

    // reset config to initial state
    configStore.resetLayoutConfig();

    // verify auth token before each page change
    if (!authStore.isAuthenticated) await authStore.verifyAuth();

    // before page access check if page requires authentication
    if (to.meta.middleware == "auth") {
        if (authStore.isAuthenticated) {
            if (
                to.meta.permission &&
                !authStore.user.permission.includes(to.meta.permission)
            ) {
                next({ name: "404" });
            } else if (to.meta.checkDetail == false) {
                next();
            }

            next();
        } else {
            next({ name: "sign-in" });
        }
    } else if (to.meta.middleware == "guest" && authStore.isAuthenticated) {
        next({ name: "dashboard" });
    } else {
        next();
    }
});

router.afterEach(() => {
    // Complete the animation of the route progress bar.
    NProgress.done();
});

export default router;
