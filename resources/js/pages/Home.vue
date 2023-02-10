<template>
    <App>
        <template v-slot:content>
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">個別会計残高</h5>
                                <p class="card-text">
                                    {{ paymentInfo.balance }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">滞納額</h5>
                                <p class="card-text">
                                    {{ paymentInfo.arrears }}
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
import { reactive, defineComponent } from "vue";
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
        return { paymentInfo };
    },
    methods: {
        getPaymentInfo() {
            HomeApiService.getPaymentInfo()
                .then((response: PaymentInfoResponse) => {
                    this.paymentInfo.arrears = response.data.arrears;
                    this.paymentInfo.balance = response.data.balance;
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
