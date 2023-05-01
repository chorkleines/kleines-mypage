<template>
    <App :isFullScreenLoading="isFullScreenLoading">
        <template v-slot:content>
            <div class="prose">
                <h1>集金記録</h1>
                <div class="flex flex-col gap-3">
                    <div v-for="accounting in accountings">
                        <div class="not-prose bg-base-100 rounded-box w-full shadow-xl p-3 px-4">
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-3 justify-between items-center">
                                    <div class="font-extrabold truncate">
                                        {{ accounting.name }}
                                    </div>
                                    <div class="text-base-content/70 text-sm">
                                        {{ accounting.formatted_price }}
                                    </div>
                                </div>
                                <div class="flex gap-3 justify-between items-center">
                                    <div class="badge badge-success badge-sm" :class="{
                                        'badge-success': accounting.is_paid,
                                        'badge-warning':
                                            !accounting.is_paid &&
                                            !accounting.is_overdue,
                                        'badge-error':
                                            !accounting.is_paid &&
                                            accounting.is_overdue,
                                    }">
                                        {{ accounting.status }}
                                    </div>
                                    <div class="text-base-content/70 text-sm" v-if="accounting.is_paid">
                                        {{ accounting.datetime }}
                                    </div>
                                    <div class="text-base-content/70 text-sm" v-if="!accounting.is_paid">
                                        支払い期限：{{ accounting.deadline }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </App>
</template>

<script lang="ts">
import { ref, defineComponent } from "vue";
import App from "@/components/App.vue";
import AccountingsApiService from "@/services/AccountingsApiService";
import { Accounting } from "@/types/AccountingsType";
import { VueGoodTable } from "vue-good-table-next";

export default defineComponent({
    components: {
        App,
        VueGoodTable,
    },
    data() {
        return {
            columns: [
                { label: "名前", field: "name" },
                { label: "金額", field: "formatted_price" },
                { label: "支払い状況", field: "status" },
                { label: "支払い期限", field: "deadline" },
                { label: "支払い日時", field: "datetime" },
            ],
        };
    },
    setup() {
        const accountings = ref<Accounting[]>([]);
        const isFullScreenLoading = ref<Boolean>(false);
        return { accountings, isFullScreenLoading };
    },
    methods: {
        async getAccountings() {
            this.accountings = await AccountingsApiService.getAccountings();
            this.accountings.forEach((accounting: Accounting) => {
                accounting.formatted_price = new Intl.NumberFormat("ja-JP", {
                    style: "currency",
                    currency: "JPY",
                }).format(accounting.price);
                accounting.is_overdue =
                    !accounting.is_paid &&
                    Date.now() > new Date(accounting.deadline);
                accounting.status = accounting.is_paid
                    ? "支払い済み"
                    : accounting.is_overdue
                        ? "期限切れ"
                        : "未払い";
            });
            this.accountings.sort((a, b) => (a.deadline > b.deadline ? -1 : 1));
            this.accountings.sort((a, b) => (a.datetime > b.datetime ? -1 : 1));
            this.accountings.sort((a, b) => (a.is_paid < b.is_paid ? -1 : 1));
        },
    },
    async mounted() {
        this.isFullScreenLoading = true;
        await this.getAccountings();
        this.isFullScreenLoading = false;
    },
});
</script>
