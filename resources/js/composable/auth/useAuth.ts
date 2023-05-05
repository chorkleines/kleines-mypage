import { ref } from "vue";
import http from "@/http-common";
import { LoginUserResponse } from "@/types/AuthType";

export function useAuth() {
    const login_user = ref<LoginUserResponse>({
        first_name: "",
        last_name: "",
        grade: null,
        part: "",
        birtyday: "",
        name_kana: "",
        user_id: null,
        email: "",
        status: "",
        email_verified_at: "",
        display_name: "",
    });

    async function fetchLoginUser(): Promise<void> {
        const response = await http.post("/auth/me");
        login_user.value = response.data;
        login_user.value.display_name = `${login_user.value.grade}${login_user.value.part} ${login_user.value.last_name}${login_user.value.first_name}`;
    }

    return { login_user, fetchLoginUser };
}
