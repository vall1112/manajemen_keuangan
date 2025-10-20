import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useLoginStudent(options = {}) {
    return useQuery({
        queryKey: ["students"],
        queryFn: async () =>
            await axios.get("/login/students").then((res: any) => res.data.data),
        ...options,
    });
}
