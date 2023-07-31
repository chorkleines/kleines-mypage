<template>
    <div class="prose">
        <div class="text-xs breadcrumbs pt-0 not-prose">
            <ul class="m-0 p-0">
                <li>
                    <NuxtLink to="/">ホーム</NuxtLink>
                </li>
                <li>集金記録</li>
            </ul>
        </div>
        <h1 class="text-2xl">集金記録</h1>
        <div class="flex flex-col gap-3">
            <div v-for="accounting in accountings">
                <div class="not-prose">
                    <NuxtLink :to="`/accountings/${accounting.accountingList.id}`"
                        class="group block bg-base-100 hover:bg-base-200 rounded-lg w-full h-full p-3 px-4">
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-3 justify-between items-center">
                                <div class="font-extrabold truncate">
                                    {{ accounting.accountingList.name }}
                                </div>
                                <div class="text-base-content/70 font-bold">
                                    {{ accounting.priceFormatted }}
                                </div>
                            </div>
                            <div class="flex gap-3 justify-between items-center">
                                <div class="badge badge-success badge-sm" :class="{
                                    'badge-success': accounting.isPaid,
                                    'badge-warning':
                                        !accounting.isPaid && !accounting.isOverdue,
                                    'badge-error': !accounting.isPaid && accounting.isOverdue,
                                }">
                                    {{ accounting.status }}
                                </div>
                                <div class="text-base-content/70 text-xs" v-if="accounting.isPaid">
                                    {{ accounting.datetimeFormatted }}
                                </div>
                                <div class="text-base-content/70 text-xs" v-if="!accounting.isPaid">
                                    支払い期限：{{ accounting.accountingList.deadlineFormatted }}
                                </div>
                            </div>
                        </div>
                    </NuxtLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
definePageMeta({
    middleware: "auth",
});
const { accountings, getAccountings } = useAccountings();

await getAccountings();
</script>
