<template>
  <div class="prose max-w-none">
    <div class="text-xs breadcrumbs pt-0 not-prose">
      <ul class="m-0 p-0">
        <li>
          <NuxtLink to="/">ホーム</NuxtLink>
        </li>
        <li>管理者メニュー</li>
        <li>団員リスト</li>
      </ul>
    </div>
    <h1 class="text-2xl">団員リスト</h1>
    <div class="max-w-lg not-prose">
      <Table
        :columns="columns"
        :rows="users"
        :initialSort="[
          { field: 'profile.grade', type: 'asc' },
          { field: 'profile.partFormatted', type: 'asc' },
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
      const { isAdmin } = useAdmin();
      await getUser();

      if (!isAdmin(user.value)) {
        return navigateTo("/");
      }
    },
  ],
});

import { Table } from "#components";
const { users, getUsers } = useUsers();
await getUsers();

const columns = [
  {
    label: "学年",
    field: "profile.grade",
    headClass: "w-20",
  },
  {
    label: "パート",
    field: "profile.partFormatted",
    sortFn: sortPart,
    headClass: "w-28",
  },
  {
    label: "氏名",
    field: "profile.fullName",
    sortable: false,
    route: "route",
  },
  {
    label: "在団 / 休団",
    field: "statusFormatted",
    sortFn: sortStatus,
    headClass: "w-28",
  },
];

users.value.forEach((user) => {
  user.route = `/admin/users/${user.id}`;
});

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
