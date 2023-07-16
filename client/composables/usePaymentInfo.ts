export const usePaymentInfo = () => {
  const { getJWT } = useAuth();
  const paymentInfo = ref({
    balance: null,
    arrears: null,
  });

  async function getPaymentInfo() {
    const { data } = await useFetch("/api/home/payment_info", {
      baseURL: "http://localhost:8000",
      method: "GET",
      headers: {
        Authorization: `Bearer ${getJWT()}`,
      },
    });
    paymentInfo.value = data.value;
  }

  return {
    paymentInfo,
    getPaymentInfo,
  };
};
