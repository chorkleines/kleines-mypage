export const useForgotPassword = () => {
  const isLoadingForgotPassword = ref(false);
  const isForgotPasswordSuccessful = ref(false);
  const failedForgotPassword = ref(false);
  const forgotPasswordFailureMessage = ref("");

  async function forgotPassword(email: string) {
    isLoadingForgotPassword.value = true;
    await getCsrfToken();
    const { status, error } = await useApiFetch("/api/password/forgot", {
      method: "POST",
      body: JSON.stringify({ email }),
    });

    if (status.value === "error") {
      failedForgotPassword.value = true;
      if (error.value?.statusCode === 500) {
        forgotPasswordFailureMessage.value = "Please wait before retrying.";
      } else {
        forgotPasswordFailureMessage.value = error.value!;
      }
      isLoadingForgotPassword.value = false;
      return;
    }

    isLoadingForgotPassword.value = false;
    isForgotPasswordSuccessful.value = true;
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
    isLoadingForgotPassword,
    forgotPassword,
    failedForgotPassword,
    forgotPasswordFailureMessage,
    isForgotPasswordSuccessful,
  };
};
