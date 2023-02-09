import http from "@/http-common";
import {
    LoginRequest,
    BearerTokenResponse,
    LoginUserResponse,
} from "@/types/AuthType";

class AuthApiService {
    getBearerToken(data: LoginRequest): Promise<BearerTokenResponse> {
        return http.post("/auth/login", data);
    }
    getLoginUser(): Promise<LoginUserResponse> {
        return http.post("/auth/me");
    }
    logout() {
        return http.post("/auth/logout");
    }
}

export default new AuthApiService();
