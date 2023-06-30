import { ref } from "vue";
import http from "@/http-common";
import { ComputedUser } from "@/types/UsersType";

export function useUsers() {
    const users = ref<ComputedUser[]>([]);

    async function fetchUsers(): Promise<void> {
        const response = await http.get("/users");
        users.value = response.data;
        users.value.forEach((user: ComputedUser) => {
            user.full_name = `${user.last_name} ${user.first_name}`;
            switch (user.status) {
                case "PRESENT":
                    user.status_formatted = "在団";
                    break;
                case "ABSENT":
                    user.status_formatted = "休団";
                    break;
                default:
                    user.status_formatted = "";
                    break;
            }
            switch (user.part) {
                case "S":
                    user.part_formatted = "Soprano";
                    break;
                case "A":
                    user.part_formatted = "Alto";
                    break;
                case "T":
                    user.part_formatted = "Tenor";
                    break;
                case "B":
                    user.part_formatted = "Bass";
                    break;
                default:
                    user.part_formatted = "";
                    break;
            }
            users.value.push(user);
        });
    }

    return { users, fetchUsers };
}
