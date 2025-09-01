import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useClassroom(options = {}) {
    return useQuery({
        queryKey: ["classrooms"],
        queryFn: async () =>
            await axios.get("/master/classrooms").then((res: any) => res.data.data),
        ...options,
    });
}
