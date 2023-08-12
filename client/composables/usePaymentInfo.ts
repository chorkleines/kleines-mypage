export const usePaymentInfo = () => {
  const paymentInfo = ref({
    balance: null,
    arrears: null,
  });

  async function getPaymentInfo() {
    const { data } = await useApiFetch("/api/home/payment-info", {
      method: "GET",
    });
    paymentInfo.value = data.value;
  }

  return {
    paymentInfo,
    getPaymentInfo,
  };
};
