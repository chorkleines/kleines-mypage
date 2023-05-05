export type LoginRequest = {
    email: string;
    password: string;
};

export type BearerTokenResponse = {
    access_token: string;
    token_type: string;
    expires_in: number;
};

export type LoginUser = {
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
};

export type LoginUserResponse = LoginUser & {
    display_name: string;
};

export type LogoutResponse = {
    message: string;
};
