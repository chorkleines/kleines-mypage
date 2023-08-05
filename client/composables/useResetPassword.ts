export const useResetPassword = () => {
  const isLoadingResetPassword = ref(false);
  const isResetPasswordSuccessful = ref(false);
  const failedResetPassword = ref(false);
  const resetPasswordFailureMessage = ref("");

  async function resetPassword(email: string) {
    isLoadingResetPassword.value = true;
    await getCsrfToken();
    const { status, error } = await useApiFetch("/api/password/forgot", {
      method: "POST",
      body: JSON.stringify({ email }),
    });

    if (status.value === "error") {
      failedResetPassword.value = true;
      if (error.value?.statusCode === 500) {
        resetPasswordFailureMessage.value = "Please wait before retrying.";
      } else {
        resetPasswordFailureMessage.value = error.value!;
      }
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
