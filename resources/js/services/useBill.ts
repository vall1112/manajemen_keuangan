import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useBill(options = {}) {
    return useQuery({
        queryKey: ["bills"],
        queryFn: async () =>
            await axios.get("/bills").then((res: any) => res.data.data),
        ...options,
    });
}
