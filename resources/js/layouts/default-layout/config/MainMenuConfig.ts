import type { MenuItem } from "@/layouts/default-layout/config/types";

const MainMenuConfig: Array<MenuItem> = [
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
    // WEBSITE
    {
        heading: "KEUANGAN",
        route: "/dashboard/keuangan",
        name: "finance",
        pages: [
            // MASTER
            {
                heading: "Tagihan",
                route: "/bill",
                name: "bill",
                keenthemesIcon: "dollar",
            },
            {
                heading: "Transaksi",
                name: "transaction",
                route: "/transaction",
                keenthemesIcon: "wallet",
            },
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
    {
        heading: "Website",
        route: "/dashboard/website",
        name: "website",
        pages: [
            // MASTER
            {
                sectionTitle: "Master",
                route: "/master",
                keenthemesIcon: "cube-3",
                name: "master",
                sub: [
                    {
                        heading: "User",
                        route: "/dashboard/master/users",
                        name: "master-user",
                    },
                    {
                        heading: "Role",
                        route: "/dashboard/master/users/roles",
                        name: "master-role",
                    },
                    {
                        heading: "Guru",
                        route: "/dashboard/master/teachers",
                        name: "master-teacher",
                    },
                    {
                        heading: "Siswa",
                        route: "/dashboard/master/students",
                        name: "master-student",
                    },
                    {
                        heading: "Kelas",
                        route: "/dashboard/master/classrooms",
                        name: "master-classroom",
                    },
                    {
                        heading: "Jurusan",
                        route: "/dashboard/master/majors",
                        name: "master-major",
                    },
                    {
                        heading: "Tahun Ajaran",
                        route: "/dashboard/master/school_years",
                        name: "master-school-year",
                    },
                    {
                        heading: "Jenis Pembayaran",
                        route: "/dashboard/master/payment_types",
                        name: "master-payment",
                    },
                ],
            },
            {
                heading: "Setting",
                route: "/dashboard/setting",
                name: "setting",
                keenthemesIcon: "setting-2",
            },
        ],
    },
    // DASHBOARD USER
    {
        pages: [
            {
                heading: "Dashboard",
                name: "user-dashboard",
                route: "/user/dashboard",
                keenthemesIcon: "element-11",
            },
        ],
    },
    // TAGIHAN USER
    {
        pages: [
            {
                heading: "Tagihan",
                name: "user-bill",
                route: "/user/bill",
                keenthemesIcon: "element-11",
            },
        ],
    },
    // TABUNGAN USER
    {
        pages: [
            {
                heading: "Tabungan",
                name: "user-savings",
                route: "/user/savings",
                keenthemesIcon: "element-11",
            },
        ],
    },
];

export default MainMenuConfig;
