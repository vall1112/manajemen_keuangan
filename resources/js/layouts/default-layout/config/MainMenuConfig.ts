import type { MenuItem } from "@/layouts/default-layout/config/types";

const MainMenuConfig: Array<MenuItem> = [
    // DASHBOARD ADMIN
    {
        pages: [
            {
                heading: "Dashboard",
                name: "dashboard",
                route: "/dashboard",
                keenthemesIcon: "element-11",
            },
        ],
    },
    // DASHBOARD BENDAHARA
    {
        pages: [
            {
                heading: "Dashboard",
                name: "bendahara-dashboard",
                route: "/bendahara/dashboard",
                keenthemesIcon: "element-11",
            },
        ],
    },
    // DASHBOARD SISWA
    {
        pages: [
            {
                heading: "Dashboard",
                name: "user-dashboard",
                route: "/student/dashboard",
                keenthemesIcon: "element-11",
            },
        ],
    },
    // WEBISTE
    {
        heading: "Master",
        route: "/master",
        name: "master",
        pages: [
            // MASTER
            {
                sectionTitle: "Master",
                route: "/master",
                keenthemesIcon: "cube-3",
                name: "master",
                sub: [
                    // USER
                    {
                        sectionTitle: "User",
                        route: "user",
                        keenthemesIcon: "bank",
                        name: "savings",
                        sub: [
                            {
                                heading: "Admin",
                                route: "/dashboard/master/users/admin",
                                name: "master-user",
                            },
                            {
                                heading: "Guru",
                                route: "/dashboard/master/users/teacher",
                                name: "master-user",
                            },
                            {
                                heading: "Siswa",
                                route: "/dashboard/master/users",
                                name: "master-user",
                            },
                        ],
                    },
                    // ROLE
                    {
                        heading: "Role",
                        route: "/dashboard/master/roles",
                        name: "master-role",
                    },
                    // GURU
                    {
                        heading: "Guru",
                        route: "/dashboard/master/teachers",
                        name: "master-teacher",
                    },
                    // SISWA
                    {
                        heading: "Siswa",
                        route: "/dashboard/master/students",
                        name: "master-student",
                    },
                    // KELAS
                    {
                        heading: "Kelas",
                        route: "/dashboard/master/classrooms",
                        name: "master-classroom",
                    },
                    // JURUSAN
                    {
                        heading: "Jurusan",
                        route: "/dashboard/master/majors",
                        name: "master-major",
                    },
                    // TAHUN AJARAN
                    {
                        heading: "Tahun Ajaran",
                        route: "/dashboard/master/school_years",
                        name: "master-school-year",
                    },
                    // JENIS PEMBAYARAN
                    {
                        heading: "Jenis Pembayaran",
                        route: "/dashboard/master/payment_types",
                        name: "master-payment",
                    },
                ],
            },
        ],
    },
    // KEUANGAN
    {
        heading: "KEUANGAN",
        route: "/dashboard/keuangan",
        name: "finance",
        pages: [
            // TAGIHAN
            {
                heading: "Tagihan",
                route: "/bill",
                name: "bill",
                keenthemesIcon: "dollar",
            },
            // TRANSAKSI TAGIHAN
            {
                heading: "Transaksi Tagihan",
                name: "transaction",
                route: "/transaction",
                keenthemesIcon: "wallet",
            },
            // KONFIRMASI PEMBAYARAN
            {
                heading: "Verifikasi Pembayaran",
                name: "transaction",
                route: "/payment/confirmation",
                keenthemesIcon: "arrows-circle",
            },
            // TABUNGAN
            {
                sectionTitle: "Tabungan",
                route: "savings",
                keenthemesIcon: "bank",
                name: "savings",
                sub: [
                    {
                        heading: "Setor",
                        route: "/savings/deposit",
                        name: "savings",
                    },
                    {
                        heading: "Tarik",
                        route: "/savings/pull",
                        name: "savings",
                    },
                    {
                        heading: "Histori",
                        route: "/savings/history",
                        name: "savings",
                    },
                    {
                        heading: "Saldo",
                        route: "/savings/balance",
                        name: "savings",
                    },
                ],
            },
        ],
    },
    // WEBISTE
    {
        heading: "Website",
        route: "/dashboard/website",
        name: "website",
        pages: [
            // PERSETUJUAN USER
            {
                heading: "Persetujuan User",
                route: "/dashboard/user-agreement",
                name: "master-user",
                keenthemesIcon: "check-square",
            },
            // PENGATURAN
            {
                heading: "Pengaturan",
                route: "/dashboard/setting",
                name: "setting",
                keenthemesIcon: "setting-2",
            },
        ],
    },
    // TAGIHAN SISWA
    {
        pages: [
            {
                heading: "Tagihan",
                name: "user-bill",
                route: "/student/bill",
                keenthemesIcon: "dollar",
            },
        ],
    },
    // {
    //     pages: [
    //         {
    //             heading: "Pengajuan Pembayaran",
    //             name: "user-bill",
    //             route: "/student/payment",
    //             keenthemesIcon: "dollar",
    //         },
    //     ],
    // },
    // TABUNGAN SISWA
    {
        pages: [
            {
                heading: "Tabungan",
                name: "user-savings",
                route: "/student/savings",
                keenthemesIcon: "bank",
            },
        ],
    },
];

export default MainMenuConfig;
