import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useUser(options = {}) {
    return useQuery({
        queryKey: ["users"],
        queryFn: async () =>
            await axios.get("/master/users").then((res: any) => res.data.data),
        ...options,
    });
}
