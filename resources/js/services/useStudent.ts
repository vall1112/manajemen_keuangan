import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useStudent(options = {}) {
    return useQuery({
        queryKey: ["students"],
        queryFn: async () =>
            await axios.get("/master/students").then((res: any) => res.data.data),
        ...options,
    });
}
