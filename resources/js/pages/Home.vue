<template>
    <App :isFullScreenLoading="isFullScreenLoading">
        <template v-slot:content>
            <div
                class="stats stats-vertical md:stats-horizontal w-full max-w-sm md:max-w-3xl"
            >
                <div class="stat">
                    <div class="stat-figure text-primary">
                        <i class="fa-solid fa-wallet text-3xl"></i>
                    </div>
                    <div class="stat-title">個別会計残高</div>
                    <div class="stat-value w-40">
                        {{
                            new Intl.NumberFormat("ja-JP", {
                                style: "currency",
                                currency: "JPY",
                            }).format(paymentInfo.balance)
                        }}
                    </div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="fa-solid fa-money-check-dollar text-3xl"></i>
                    </div>
                    <div class="stat-title">滞納額</div>
                    <div class="stat-value w-40">
                        {{
                            new Intl.NumberFormat("ja-JP", {
                                style: "currency",
                                currency: "JPY",
                            }).format(paymentInfo.arrears)
                        }}
                    </div>
                </div>
            </div>
        </template>
    </App>
</template>

<script lang="ts">
import { reactive, ref, defineComponent } from "vue";
import App from "@/components/App.vue";
import HomeApiService from "@/services/HomeApiService";
import { PaymentInfoResponse } from "@/types/HomeType";

export default defineComponent({
    components: {
        App,
    },
    setup() {
        const paymentInfo = reactive<PaymentInfoResponse>({
            arrears: null,
            balance: null,
        });
        const isFullScreenLoading = ref<Boolean>(false);
        return { paymentInfo, isFullScreenLoading };
    },
    methods: {
        getPaymentInfo() {
            this.isFullScreenLoading = true;
            HomeApiService.getPaymentInfo()
                .then((response: PaymentInfoResponse) => {
                    this.paymentInfo.arrears = response.data.arrears;
                    this.paymentInfo.balance = response.data.balance;
                    this.isFullScreenLoading = false;
                })
                .catch((e: Error) => {
                    console.log(e);
                });
        },
    },
    mounted() {
        this.getPaymentInfo();
    },
});
</script>
