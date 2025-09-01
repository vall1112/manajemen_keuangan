import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useTeacher(options = {}) {
    return useQuery({
        queryKey: ["teachers"],
        queryFn: async () =>
            await axios.get("/master/teachers").then((res: any) => res.data.data),
        ...options,
    });
}
