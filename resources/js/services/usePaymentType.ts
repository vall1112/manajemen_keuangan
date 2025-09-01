import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function usePaymentType(options = {}) {
    return useQuery({
        queryKey: ["payment-types"],
        queryFn: async () =>
            await axios.get("/master/payment-types").then((res: any) => res.data.data),
        ...options,
    });
}
