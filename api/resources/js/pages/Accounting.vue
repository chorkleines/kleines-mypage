<template>
    <App :isFullScreenLoading="isFullScreenLoading">
        <template v-slot:content>
            <div class="prose">
                <div class="text-xs breadcrumbs pt-0">
                    <ul class="m-0 p-0">
                        <li>
                            <router-link :to="{ name: 'home' }"
                                >ホーム</router-link
                            >
                        </li>
                        <li>
                            <router-link :to="{ name: 'accountings' }"
                                >集金記録</router-link
                            >
                        </li>
                        <li>
                            {{ accounting.name }}
                        </li>
                    </ul>
                </div>

                <div
                    class="block bg-base-100 rounded-lg w-full h-full p-4 mt-2"
                >
                    <div class="flex flex-col gap-2 py-2">
                        <div class="font-extrabold truncate text-center">
                            {{ accounting.name }}
                        </div>
                        <div class="font-extrabold text-xl text-center">
                            {{ accounting.price_formatted }}
                        </div>
                        <div class="flex justify-center">
                            <div
                                class="badge"
                                :class="{
                                    'badge-success': accounting.is_paid,
                                    'badge-warning':
                                        !accounting.is_paid &&
                                        !accounting.is_overdue,
                                    'badge-error':
                                        !accounting.is_paid &&
                                        accounting.is_overdue,
                                }"
                            >
                                {{ accounting.status }}
                            </div>
                        </div>
                    </div>
                    <hr class="my-3" />
                    <div class="flex flex-col gap-1">
                        <div class="flex justify-between">
                            <div class="text-base-content/70 text-sm">
                                支払い期限
                            </div>
                            <div class="text-base-content/70 text-sm">
                                {{ accounting.deadline_formatted }}
                            </div>
                        </div>
                        <div
                            class="flex justify-between"
                            v-if="accounting.is_paid"
                        >
                            <div class="text-base-content/70 text-sm">
                                支払い日時
                            </div>
                            <div class="text-base-content/70 text-sm">
                                {{ accounting.datetime_formatted }}
                            </div>
                        </div>
                    </div>
                    <hr class="my-3" v-if="accounting.is_paid" />
                    <div class="flex flex-col gap-1" v-if="accounting.is_paid">
                        <div
                            class="flex justify-between"
                            v-for="payment in payments"
                        >
                            <div class="text-base-content/70 text-sm">
                                {{ payment.method_formatted }}
                            </div>
                            <div class="text-base-content/70 text-sm">
                                {{ payment.price_formatted }}
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div class="font-extrabold text-sm">支払い合計</div>
                            <div class="font-extrabold text-sm">
                                {{ accounting.price_formatted }}
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
import { useAccounting } from "@/composable/useAccounting";

export default defineComponent({
    components: {
        App,
    },
    setup() {
        const { accounting, payments, fetchAccounting } = useAccounting();
        const isFullScreenLoading = ref<Boolean>(false);
        return {
            accounting,
            payments,
            fetchAccounting,
            isFullScreenLoading,
        };
    },
    async mounted() {
        this.isFullScreenLoading = true;
        await this.fetchAccounting(this.$route.params.id);
        this.isFullScreenLoading = false;
    },
});
</script>
