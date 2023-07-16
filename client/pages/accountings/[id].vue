<template>
    <div class="prose">
        <div class="text-xs breadcrumbs pt-0 not-prose">
            <ul class="m-0 p-0">
                <li>
                    <NuxtLink to="/">ホーム</NuxtLink>
                </li>
                <li>
                    <NuxtLink to="/accountings">集金記録</NuxtLink>
                </li>
                <li>
                    {{ accounting.name }}
                </li>
            </ul>
        </div>

        <div class="block bg-base-100 rounded-lg w-full h-full p-4 mt-2">
            <div class="flex flex-col gap-2 py-2">
                <div class="font-extrabold truncate text-center">
                    {{ accounting.name }}
                </div>
                <div class="font-extrabold text-xl text-center">
                    {{ accounting.priceFormatted }}
                </div>
                <div class="flex justify-center">
                    <div class="badge" :class="{
                        'badge-success': accounting.isPaid,
                        'badge-warning': !accounting.isPaid && !accounting.isOverdue,
                        'badge-error': !accounting.isPaid && accounting.isOverdue,
                    }">
                        {{ accounting.status }}
                    </div>
                </div>
            </div>
            <hr class="my-3" />
            <div class="flex flex-col gap-1">
                <div class="flex justify-between">
                    <div class="text-base-content/70 text-sm">支払い期限</div>
                    <div class="text-base-content/70 text-sm">
                        {{ accounting.deadlineFormatted }}
                    </div>
                </div>
                <div class="flex justify-between" v-if="accounting.isPaid">
                    <div class="text-base-content/70 text-sm">支払い日時</div>
                    <div class="text-base-content/70 text-sm">
                        {{ accounting.datetimeFormatted }}
                    </div>
                </div>
            </div>
            <hr class="my-3" v-if="accounting.isPaid" />
            <div class="flex flex-col gap-1" v-if="accounting.isPaid">
                <div class="flex justify-between" v-for="payment in payments">
                    <div class="text-base-content/70 text-sm">
                        {{ payment.methodFormatted }}
                    </div>
                    <div class="text-base-content/70 text-sm">
                        {{ payment.priceFormatted }}
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="font-extrabold text-sm">支払い合計</div>
                    <div class="font-extrabold text-sm">
                        {{ accounting.priceFormatted }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
definePageMeta({
    middleware: "auth",
});

const route = useRoute();
const { id } = route.params;
const { accounting, payments, getAccounting } = useAccounting();
await getAccounting(id);
</script>
