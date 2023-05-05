import { ref } from "vue";
import http from "@/http-common";
import { PaymentInfoResponse } from "@/types/HomeType";

export function usePaymentInfo() {
    const payment_info = ref<PaymentInfoResponse>({
        balance: null,
        arrears: null,
    });

    async function fetchPaymentInfo(): Promise<void> {
        const response = await http.get("/home/payment_info");
        payment_info.value = response.data;
    }

    return { payment_info, fetchPaymentInfo };
}
