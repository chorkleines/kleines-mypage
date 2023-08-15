<template>
  <div class="prose max-w-none">
    <div class="text-xs breadcrumbs pt-0 not-prose">
      <ul class="m-0 p-0">
        <li>
          <NuxtLink to="/">ホーム</NuxtLink>
        </li>
        <li>管理者メニュー</li>
        <li>
          <NuxtLink to="/admin/users">団員リスト</NuxtLink>
        </li>
        <li>{{ user.profile.displayName }}</li>
      </ul>
    </div>
    <h1 class="text-2xl">{{ user.profile.displayName }}</h1>
    <div class="max-w-lg not-prose">
      <div class="flex justify-end mb-3">
        <NuxtLink
          :to="`/admin/users/${id}/edit`"
          class="btn btn-outline btn-primary btn-sm"
          ><font-awesome-icon icon="pen-to-square" />編集</NuxtLink
        >
      </div>
      <div class="overflow-x-auto">
        <table class="table table-sm my-0">
          <tbody>
            <tr>
              <th class="w-36">名前（姓）</th>
              <td>{{ user.profile.lastName }}</td>
            </tr>
            <tr>
              <th>名前（名）</th>
              <td>{{ user.profile.firstName }}</td>
            </tr>
            <tr>
              <th>名前（かな）</th>
              <td
                v-if="
                  user.profile.nameKana !== undefined &&
                  user.profile.nameKana !== null
                "
              >
                {{ user.profile.nameKana }}
              </td>
              <td v-else class="text-base-content/[.3]">未入力</td>
            </tr>
            <tr>
              <th>学年</th>
              <td>{{ user.profile.grade }}</td>
            </tr>
            <tr>
              <th>パート</th>
              <td>{{ user.profile.partFormatted }}</td>
            </tr>
            <tr v-if="hasRoles(['MASTER', 'MANAGER'])">
              <th>メールアドレス</th>
              <td>{{ user.email }}</td>
            </tr>
            <tr>
              <th>在団状況</th>
              <td>{{ user.statusFormatted }}</td>
            </tr>
            <tr v-if="hasRoles(['MASTER', 'MANAGER'])">
              <th>誕生日</th>
              <td>{{ user.profile.birthdayFormatted }}</td>
            </tr>
          </tbody>
        </table>
      </div>
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

const route = useRoute();
const { hasRoles } = useAuth();
const { id } = route.params;
const { user, getUser } = useAdminUsers();
await getUser(id);
</script>
