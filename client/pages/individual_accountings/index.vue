<template>
    <div class="prose">
        <div class="text-xs breadcrumbs pt-0 not-prose">
            <ul class="m-0 p-0">
                <li>
                    <NuxtLink to="/">ホーム</NuxtLink>
                </li>
                <li>個別会計</li>
            </ul>
        </div>
        <h1 class="text-2xl">個別会計</h1>
        <div class="overflow-x-auto w-full not-prose max-w-lg">
            <vue-good-table :columns="columns" :rows="individualAccountings" :sort-options="{
                enabled: true,
                initialSortBy: [],
            }" :pagination-options="{
    enabled: true,
    perPage: 20,
}" styleClass="table whitespace-nowrap w-full mb-3">
                <template #table-row="props">
                    <span v-if="props.column.field == 'name'">
                        <NuxtLink :to="`/accountings/${props.row.accountingPayment.accountingRecord.accountingList.id}`"
                            v-if="props.row.accountingPayment !== undefined &&
                                props.row.accountingPayment !== null
                                " class="underline">{{ props.row.name }}</NuxtLink>
                        <span v-else>{{ props.row.name }}</span>
                    </span>
                    <span v-else>
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                </template>
            </vue-good-table>
        </div>
    </div>
</template>

<script setup lang="ts">
definePageMeta({
    middleware: "auth",
});
import { VueGoodTable } from "vue-good-table-next";
const { individualAccountings, getIndividualAccountings } =
    useIndividualAccountings();

await getIndividualAccountings();

const columns = [
    {
        label: "日付",
        field: "datetimeFormatted",
    },
    {
        label: "項目",
        field: "name",
        sortable: false,
        html: true,
    },
    {
        label: "金額",
        field: "priceFormatted",
    },
];
</script>
