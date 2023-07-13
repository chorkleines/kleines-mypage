export type User = {
    userId: number;
    email: string;
    firstName: string;
    lastName: string;
    nameKana?: string;
    grade: number;
    part: string;
    birthday: string;
    status: string;
    emailVerifiedAt: string;
    createdAt: string;
    updatedAt: string;
};

export type BearerToken = {
    accessToken: string;
    expiresIn: number;
    tokenType: string;
};

export const useUser = () => {
    return useState<User | null>("user", () => null);
};

export const useBearerToken = () => {
    return useState<User | null>("bearerToken", () => null);
};

export const useAuth = () => {
    const user = useUser();
    const isLoggedIn = computed(() => !!user.value);

    const setUser = (u: User) => {
        if (u == null) {
            user.value = null;
        } else {
            user.value = {};
            user.value.userId = u.user_id;
            user.value.email = u.email;
            user.value.firstName = u.first_name;
            user.value.lastName = u.last_name;
            user.value.nameKana = u.name_kana;
            user.value.grade = u.grade;
            user.value.part = u.part;
            user.value.birthday = u.birthday;
            user.value.status = u.status;
            user.value.emailVerifiedAt = u.email_verified_at;
            user.value.createdAt = u.created_at;
            user.value.updatedAt = u.updated_at;
        }
    };

    async function login(email: string, password: string) {
        if (isLoggedIn.value) return;
        const jwt = useCookie("jwt");
        jwt.value = null;

        const { data } = await useFetch("/api/auth/login", {
            baseURL: "http://localhost:8000",
            method: "POST",
            body: JSON.stringify({ email, password }),
        });

        jwt.value = data.value.access_token;
    }

    async function getUser() {
        const jwt = useCookie("jwt");
        const { data } = await useFetch("/api/auth/me", {
            baseURL: "http://localhost:8000",
            method: "POST",
            headers: {
                Authorization: `Bearer ${jwt.value}`,
            },
        });
        setUser(data.value);
    }

    return {
        user,
        isLoggedIn,
        login,
        getUser,
    };
};
