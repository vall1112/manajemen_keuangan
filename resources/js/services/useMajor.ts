import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useMajor(options = {}) {
    return useQuery({
        queryKey: ["majors"],
        queryFn: async () =>
            await axios.get("/master/majors").then((res: any) => res.data.data),
        ...options,
    });
}
