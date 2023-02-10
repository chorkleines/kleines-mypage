import http from "@/http-common";
import { PaymentInfoResponse } from "@/types/HomeType";

class AuthApiService {
    getPaymentInfo(): Promise<PaymentInfoResponse> {
        return http.get("/home/payment_info");
    }
}

export default new AuthApiService();
