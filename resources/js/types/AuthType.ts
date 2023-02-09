export default interface LoginRequest {
    email: string;
    password: string;
}

export default interface BearerTokenResponse {
    access_token: string;
    token_type: string;
    expires_in: number;
}

export default interface LoginUser {
    user_id: number;
    email: string;
    status: string;
}

export default interface LoginUserResponse {
    user_id: number;
    email: string;
    status: string;
    email_verified_at: string;
    created_at: string;
    updated_at: string;
}

export default interface LogoutResponse {
    message: string;
}
