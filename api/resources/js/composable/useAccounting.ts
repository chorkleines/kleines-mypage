import { ref } from "vue";
import http from "@/http-common";
import { ComputedAccounting, ComputedPayment } from "@/types/AccountingsType";

export function useAccounting() {
    const accounting = ref<ComputedAccounting>({
        id: null,
        accounting_id: null,
        admin: "",
        name: "",
        price: null,
        deadline: "",
        is_paid: null,
        datetime: "",
    });
    const payments = ref<ComputedPayment[]>([]);

    async function fetchAccounting(id: number): Promise<void> {
        const response = await http.get(`/accountings/${id}`);
        accounting.value = response.data.accounting;
        payments.value = response.data.payments;

        // compute accounting
        accounting.value.price_formatted = new Intl.NumberFormat("ja-JP", {
            style: "currency",
            currency: "JPY",
        }).format(accounting.value.price);
        accounting.value.is_overdue =
            !accounting.value.is_paid &&
            Date.now() > new Date(accounting.value.deadline).getTime();
        accounting.value.status = accounting.value.is_paid
            ? "支払い済み"
            : accounting.value.is_overdue
            ? "期限切れ"
            : "未払い";
        accounting.value.datetime_formatted = new Date(
            accounting.value.datetime
        ).toLocaleString();
        accounting.value.deadline_formatted = new Date(
            accounting.value.deadline
        ).toLocaleDateString();

        // compute payments
        if (accounting.value.is_paid) {
            payments.value.forEach((payment: ComputedPayment) => {
                payment.price_formatted = new Intl.NumberFormat("ja-JP", {
                    style: "currency",
                    currency: "JPY",
                }).format(payment.price);
                switch (payment.method) {
                    case "CASH":
                        payment.method_formatted = "現金";
                        break;
                    case "INDIVIDUAL_ACCOUNTING":
                        payment.method_formatted = "個別会計";
                        break;
                    default:
                        payment.method_formatted = "";
                        break;
                }
            });
        }
        console.log(accounting.value);
        console.log(payments.value);
    }

    return { accounting, payments, fetchAccounting };
}
