<template>
  <div class="prose max-w-none">
    <div class="text-xs breadcrumbs pt-0 not-prose">
      <ul class="m-0 p-0">
        <li>
          <NuxtLink to="/">ホーム</NuxtLink>
        </li>
        <li>管理者メニュー</li>
        <li>
          <NuxtLink to="/admin/individual_accountings">個別会計</NuxtLink>
        </li>
        <li>{{ individualAccountingList.name }}</li>
      </ul>
    </div>
    <h1 class="text-2xl">{{ individualAccountingList.name }}</h1>
    <div class="max-w-2xl not-prose">
      <Table
        :columns="columns"
        :rows="individualAccountingList.individualAccountingRecords"
        :initialSort="[
          { field: 'user.profile.grade', type: 'asc' },
          { field: 'user.profile.partFormatted', type: 'asc' },
        ]"
        :initialPerPage="10"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: [
    "auth",
    async function (_to, _from) {
      const { user, getUser } = useAuth();
      const { isMaster, isAccountant } = useAdmin();
      await getUser();

      if (!(isMaster(user.value) || isAccountant(user.value))) {
        return navigateTo("/");
      }
    },
  ],
});
import { Table } from "#components";
const { individualAccountingList, getIndividualAccountingList } =
  useAdminIndividualAccountings();
const route = useRoute();
const { id } = route.params;

const columns = [
  {
    label: "学年",
    field: "user.profile.grade",
    headClass: "w-20",
  },
  {
    label: "パート",
    field: "user.profile.partFormatted",
    sortFn: sortPart,
    headClass: "w-28",
  },
  {
    label: "氏名",
    field: "user.profile.fullName",
    sortable: false,
    route: "route",
  },
  {
    label: "金額",
    field: "priceFormatted",
  },
  {
    label: "在団 / 休団",
    field: "user.statusFormatted",
    sortFn: sortStatus,
    headClass: "w-28",
  },
  {
    label: "日付",
    field: "datetimeFormatted",
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

await getIndividualAccountingList(id);
</script>
