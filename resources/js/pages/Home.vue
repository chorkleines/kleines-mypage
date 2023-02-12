<template>
    <App :isFullScreenLoading="isFullScreenLoading">
        <template v-slot:content>
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">個別会計残高</h5>
                                <p class="card-text">
                                    {{
                                        new Intl.NumberFormat("ja-JP", {
                                            style: "currency",
                                            currency: "JPY",
                                        }).format(paymentInfo.balance)
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">滞納額</h5>
                                <p class="card-text">
                                    {{
                                        new Intl.NumberFormat("ja-JP", {
                                            style: "currency",
                                            currency: "JPY",
                                        }).format(paymentInfo.arrears)
                                    }}
                                </p>
                            </div>
                        </div>
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
