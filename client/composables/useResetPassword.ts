export const useResetPassword = () => {
  const isLoadingResetPassword = ref(false);
  const isResetPasswordSuccessful = ref(false);
  const failedResetPassword = ref(false);
  const resetPasswordFailureMessage = ref("");

  async function resetPassword(
    email: string,
    password: string,
    passwordConfirmation: string,
    token: string,
  ) {
    isLoadingResetPassword.value = true;
    await getCsrfToken();
    const { status, error } = await useApiFetch("/api/password/reset", {
      method: "POST",
      body: JSON.stringify({
        email,
        password,
        password_confirmation: passwordConfirmation,
        token,
      }),
    });

    if (status.value === "error") {
      failedResetPassword.value = true;
      resetPasswordFailureMessage.value = error.value!;
      isLoadingResetPassword.value = false;
      return;
    }

    isLoadingResetPassword.value = false;
    isResetPasswordSuccessful.value = true;
  }

  async function getCsrfToken() {
    await useFetch("/sanctum/csrf-cookie", {
      baseURL: "http://localhost:8000",
      method: "GET",
      mode: "cors",
      credentials: "include",
    });
  }

  return {
    isLoadingResetPassword,
    resetPassword,
    failedResetPassword,
    resetPasswordFailureMessage,
    isResetPasswordSuccessful,
  };
};
