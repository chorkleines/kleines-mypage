<template>
  <div class="prose max-w-none">
    <div class="text-xs breadcrumbs pt-0 not-prose">
      <ul class="m-0 p-0">
        <li>
          <NuxtLink to="/">ホーム</NuxtLink>
        </li>
        <li>管理者メニュー</li>
        <li>個別会計</li>
      </ul>
    </div>
    <h1 class="text-2xl">個別会計</h1>
    <div class="max-w-lg not-prose">
      <Table
        :columns="columns"
        :rows="individualAccountingLists"
        :initialSort="[{ field: 'datetime', type: 'desc' }]"
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
const { individualAccountingLists, getIndividualAccountingLists } =
  useAdminIndividualAccountings();

const columns = [
  {
    label: "項目",
    field: "name",
    headClass: "w-20",
    route: "route",
  },
  {
    label: "作成日時",
    field: "datetime",
    headClass: "w-28",
  },
];

await getIndividualAccountingLists();
</script>
