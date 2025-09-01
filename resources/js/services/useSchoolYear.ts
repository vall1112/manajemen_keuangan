import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useSchoolYear(options = {}) {
    return useQuery({
        queryKey: ["school-years"],
        queryFn: async () =>
            await axios.get("/master/school-years").then((res: any) => res.data.data),
        ...options,
    });
}
