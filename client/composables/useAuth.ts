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
  roles: string[];
  createdAt: string;
  updatedAt: string;
  displayName: string;
};

export const useUser = () => {
  return useState<User | null>("user", () => null);
};

export const useAuth = () => {
  const user = useUser();
  const isLoggedIn = computed(() => !!user.value);
  const failedLogin = ref(false);
  const loginFailureMessage = ref("");
  const isLoadingLogin = ref(false);

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
      user.value.roles = u.roles;
      user.value.createdAt = u.created_at;
      user.value.updatedAt = u.updated_at;
      user.value.displayName = `${u.grade}${u.part} ${u.last_name}${u.first_name}`;
    }
  };

  async function login(email: string, password: string) {
    if (isLoggedIn.value) return;
    isLoadingLogin.value = true;
    await getCsrfToken();

    const { status, error } = await useApiFetch("/login", {
      method: "POST",
      body: JSON.stringify({ email, password }),
    });

    if (status.value === "error") {
      failedLogin.value = true;
      if (error.value?.statusCode === 401) {
        loginFailureMessage.value =
          "メールアドレスまたはパスワードが間違っています";
      } else {
        loginFailureMessage.value = error.value!;
      }
      isLoadingLogin.value = false;
      return;
    }

    isLoadingLogin.value = false;
  }

  async function logout() {
    await useApiFetch("/logout", {
      method: "POST",
    });
    const xsrfToken = useCookie("XSRF-TOKEN");
    xsrfToken.value = null;
    setUser(null);
  }

  async function getUser() {
    const { data } = await useApiFetch("/api/me", {
      method: "GET",
    });
    setUser(data.value);
  }

  async function getCsrfToken() {
    await useFetch("/sanctum/csrf-cookie", {
      baseURL:
        process.env.NODE_ENV == "development"
          ? "http://localhost:8000"
          : "https://mypage.chorkleines.com/api/public",
      method: "GET",
      mode: "cors",
      credentials: "include",
    });
  }

  return {
    user,
    isLoggedIn,
    login,
    logout,
    getUser,
    failedLogin,
    loginFailureMessage,
    isLoadingLogin,
  };
};
