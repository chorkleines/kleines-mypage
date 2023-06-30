import { ref } from "vue";
import http from "@/http-common";
import { ComputedAccounting } from "@/types/AccountingsType";

export function useAccountings() {
    const accountings = ref<ComputedAccounting[]>([]);

    async function fetchAccountings(): Promise<void> {
        const response = await http.get("/accountings");
        accountings.value = response.data;
        accountings.value.forEach((accounting: ComputedAccounting) => {
            accounting.price_formatted = new Intl.NumberFormat("ja-JP", {
                style: "currency",
                currency: "JPY",
            }).format(accounting.price);
            accounting.is_overdue =
                !accounting.is_paid &&
                Date.now() > new Date(accounting.deadline).getTime();
            accounting.status = accounting.is_paid
                ? "支払い済み"
                : accounting.is_overdue
                ? "期限切れ"
                : "未払い";
            accounting.datetime_formatted = new Date(
                accounting.datetime
            ).toLocaleString();
            accounting.deadline_formatted = new Date(
                accounting.deadline
            ).toLocaleDateString();
        });

        accountings.value.sort(function (a, b) {
            const a_datetime = new Date(a.datetime);
            const b_datetime = new Date(b.datetime);
            const a_deadline = new Date(a.deadline);
            const b_deadline = new Date(b.deadline);
            return (
                a.is_paid - b.is_paid ||
                b_datetime.getTime() - a_datetime.getTime() ||
                b_deadline.getTime() - a_deadline.getTime()
            );
        });
    }

    return { accountings, fetchAccountings };
}
