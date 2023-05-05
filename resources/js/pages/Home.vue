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
                            }).format(payment_info.balance)
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
                            }).format(payment_info.arrears)
                        }}
                    </div>
                </div>
            </div>
        </template>
    </App>
</template>

<script lang="ts">
import { ref, defineComponent } from "vue";
import App from "@/components/App.vue";
import { usePaymentInfo } from "@/composable/usePaymentInfo";

export default defineComponent({
    components: {
        App,
    },
    setup() {
        const { payment_info, fetchPaymentInfo } = usePaymentInfo();
        const isFullScreenLoading = ref<Boolean>(false);
        return { payment_info, fetchPaymentInfo, isFullScreenLoading };
    },
    async mounted() {
        this.isFullScreenLoading = true;
        await this.fetchPaymentInfo();
        this.isFullScreenLoading = false;
    },
});
</script>
