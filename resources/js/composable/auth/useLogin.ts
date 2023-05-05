import { ref } from "vue";
import http from "@/http-common";
import { BearerTokenResponse } from "@/types/AuthType";
import { useRouter } from "vue-router";

export function useLogin() {
    const bearer_token = ref<BearerTokenResponse>({
        access_token: "",
        token_type: "",
        expires_in: "",
    });
    const isLoadingLogin = ref(false);
    const failedLogin = ref(false);
    const loginFailureMessage = ref("");
    const router = useRouter();

    async function fetchBearerToken(data: LoginRequest): Promise<void> {
        isLoadingLogin.value = true;
        failedLogin.value = false;
        loginFailureMessage.value = "";
        await http
            .post("/auth/login", data)
            .then((response: BearerTokenResponse) => {
                bearer_token.value = response.data;
                localStorage.setItem(
                    "jwt",
                    "Bearer " + bearer_token.value.access_token
                );
                router.push("/");
            })
            .catch((e: Error) => {
                failedLogin.value = true;
                loginFailureMessage.value = e.message;
                if (e.response.status === 401) {
                    loginFailureMessage.value = "ログイン情報が誤っています";
                }
                isLoadingLogin.value = false;
            });
    }

    return {
        bearer_token,
        isLoadingLogin,
        failedLogin,
        loginFailureMessage,
        fetchBearerToken,
    };
}
