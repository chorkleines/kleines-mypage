import http from "@/http-common";

export function useLogout() {
    async function logout(): Promise<void> {
        return http.post("/auth/logout");
    }

    return { logout };
}
