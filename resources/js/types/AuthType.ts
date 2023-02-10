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
    firstName: string;
    lastName: string;
    grade: number;
    part: string;
    birtyday: string;
    nameKana: string;
    user_id: number;
    email: string;
    status: string;
}

export default interface LoginUserResponse {
    first_name: string;
    last_name: string;
    grade: number;
    part: string;
    birtyday: string;
    name_kana: string;
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
