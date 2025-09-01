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
];

export default MainMenuConfig;
