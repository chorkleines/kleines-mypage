export type User = {
    first_name: string;
    last_name: string;
    grade: number;
    part: string;
    name_kana?: string;
    user_id: number;
    status: string;
};

export type UsersResponse = User[];
