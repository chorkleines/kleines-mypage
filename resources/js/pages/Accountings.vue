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
                        <li>集金記録</li>
                    </ul>
                </div>
                <h1 class="text-2xl">集金記録</h1>
                <div class="flex flex-col gap-3">
                    <div v-for="accounting in accountings">
                        <div class="not-prose">
                            <router-link
                                :to="{
                                    name: 'accounting',
                                    params: { id: accounting.accounting_id },
                                }"
                                class="group block bg-base-100 hover:bg-base-200 rounded-lg w-full h-full p-3 px-4"
                            >
                                <div class="flex flex-col gap-2">
                                    <div
                                        class="flex gap-3 justify-between items-center"
                                    >
                                        <div class="font-extrabold truncate">
                                            {{ accounting.name }}
                                        </div>
                                        <div
                                            class="text-base-content/70 font-bold"
                                        >
                                            {{ accounting.price_formatted }}
                                        </div>
                                    </div>
                                    <div
                                        class="flex gap-3 justify-between items-center"
                                    >
                                        <div
                                            class="badge badge-success badge-sm"
                                            :class="{
                                                'badge-success':
                                                    accounting.is_paid,
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
                                        <div
                                            class="text-base-content/70 text-xs"
                                            v-if="accounting.is_paid"
                                        >
                                            {{ accounting.datetime }}
                                        </div>
                                        <div
                                            class="text-base-content/70 text-xs"
                                            v-if="!accounting.is_paid"
                                        >
                                            支払い期限：{{
                                                accounting.deadline
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </router-link>
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
import { useAccountings } from "@/composable/useAccountings";

export default defineComponent({
    components: {
        App,
    },
    setup() {
        const { accountings, fetchAccountings } = useAccountings();
        const isFullScreenLoading = ref<Boolean>(false);
        return { accountings, fetchAccountings, isFullScreenLoading };
    },
    async mounted() {
        this.isFullScreenLoading = true;
        await this.fetchAccountings();
        this.isFullScreenLoading = false;
    },
});
</script>
