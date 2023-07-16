<template>
  <div class="prose">
    <div class="text-xs breadcrumbs pt-0">
      <ul class="m-0 p-0">
        <li>
          <NuxtLink to="/">ホーム</NuxtLink>
        </li>
        <li>団員リスト</li>
      </ul>
    </div>
    <h1 class="text-2xl">団員リスト</h1>
    <div class="overflow-x-auto w-full not-prose max-w-lg">
      <vue-good-table
        :columns="columns"
        :rows="users"
        :sort-options="{
          enabled: true,
          initialSortBy: [
            { field: 'grade', type: 'desc' },
            { field: 'partFormatted', type: 'asc' },
          ],
        }"
        :pagination-options="{
          enabled: true,
          perPage: 20,
        }"
        styleClass="table whitespace-nowrap w-full mb-3"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: "auth",
});
import { VueGoodTable } from "vue-good-table-next";
const { users, getUsers } = useUsers();

await getUsers();

const columns = [
  {
    label: "学年",
    field: "grade",
    width: "5rem",
  },
  {
    label: "パート",
    field: "partFormatted",
    sortFn: sortPart,
    width: "7rem",
  },
  {
    label: "氏名",
    field: "fullName",
    sortable: false,
  },
  {
    label: "在団 / 休団",
    field: "statusFormatted",
    sortFn: sortStatus,
    width: "7rem",
  },
];

function sortPart(x: string, y: string, _col, _rowX, _rowY) {
  const partOrder = ["Soprano", "Alto", "Tenor", "Bass"];
  const xIndex = partOrder.indexOf(x);
  const yIndex = partOrder.indexOf(y);
  return xIndex < yIndex ? -1 : xIndex > yIndex ? 1 : 0;
}

function sortStatus(x: string, y: string, _col, _rowX, _rowY) {
  const statusOrder = ["在団", "休団"];
  const xIndex = statusOrder.indexOf(x);
  const yIndex = statusOrder.indexOf(y);
  return xIndex < yIndex ? -1 : xIndex > yIndex ? 1 : 0;
}
</script>
