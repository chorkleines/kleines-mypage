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
      <div class="overflow-x-auto">
        <table class="table whitespace-nowrap">
          <tbody>
            <EditableRow
              :user="user"
              name="名前（姓）"
              field="profile.lastName"
              v-slot="slotProps"
              :refresh="refresh"
              :alerts="alerts"
              :required="true"
            >
              <input
                type="text"
                :value="slotProps.newValue"
                @input="slotProps.updateValue($event.target.value)"
                class="input input-bordered input-sm w-full max-w-xs"
              />
            </EditableRow>
            <EditableRow
              :user="user"
              name="名前（名）"
              field="profile.firstName"
              v-slot="slotProps"
              :refresh="refresh"
              :alerts="alerts"
              :required="true"
            >
              <input
                type="text"
                :value="slotProps.newValue"
                @input="slotProps.updateValue($event.target.value)"
                class="input input-bordered input-sm w-full max-w-xs"
              />
            </EditableRow>
            <EditableRow
              :user="user"
              name="名前（かな）"
              field="profile.nameKana"
              v-slot="slotProps"
              :refresh="refresh"
              :alerts="alerts"
            >
              <input
                type="text"
                :value="slotProps.newValue"
                @input="slotProps.updateValue($event.target.value)"
                class="input input-bordered input-sm w-full max-w-xs"
              />
            </EditableRow>
            <EditableRow
              :user="user"
              name="学年"
              field="profile.grade"
              v-slot="slotProps"
              :refresh="refresh"
              :alerts="alerts"
              :required="true"
            >
              <input
                type="number"
                :value="slotProps.newValue"
                @input="slotProps.updateValue($event.target.value)"
                class="input input-bordered input-sm w-full max-w-xs"
              />
            </EditableRow>
            <EditableRow
              :user="user"
              name="パート"
              field="profile.part"
              formattedField="profile.partFormatted"
              v-slot="slotProps"
              :refresh="refresh"
              :alerts="alerts"
              :required="true"
            >
              <select
                class="select select-bordered select-sm w-full max-w-xs"
                :value="slotProps.newValue"
                @input="slotProps.updateValue($event.target.value)"
              >
                <option disabled selected>選択肢を選ぶ</option>
                <option value="S">Soprano</option>
                <option value="A">Alto</option>
                <option value="T">Tenor</option>
                <option value="B">Bass</option>
              </select>
            </EditableRow>
            <EditableRow
              :user="user"
              name="メールアドレス"
              field="email"
              v-slot="slotProps"
              v-if="hasRoles(['MASTER', 'MANAGER'])"
              :refresh="refresh"
              :alerts="alerts"
              :required="true"
            >
              <input
                type="email"
                :value="slotProps.newValue"
                @input="slotProps.updateValue($event.target.value)"
                class="input input-bordered input-sm w-full max-w-xs"
              />
            </EditableRow>
            <EditableRow
              :user="user"
              name="在団状況"
              field="status"
              formattedField="statusFormatted"
              v-slot="slotProps"
              :refresh="refresh"
              :alerts="alerts"
              :required="true"
            >
              <select
                class="select select-bordered select-sm w-full max-w-xs"
                :value="slotProps.newValue"
                @input="slotProps.updateValue($event.target.value)"
              >
                <option disabled selected>選択肢を選ぶ</option>
                <option value="PRESENT">在団</option>
                <option value="ABSENT">休団</option>
                <option value="RESIGNED">退団</option>
              </select>
            </EditableRow>
            <EditableRow
              :user="user"
              name="誕生日"
              field="profile.birthday"
              formattedField="profile.birthdayFormatted"
              v-slot="slotProps"
              v-if="hasRoles(['MASTER', 'MANAGER'])"
              :refresh="refresh"
              :alerts="alerts"
            >
              <input
                type="date"
                :value="slotProps.newValue"
                @input="slotProps.updateValue($event.target.value)"
                class="input input-bordered input-sm w-full max-w-xs"
              />
            </EditableRow>
          </tbody>
        </table>
      </div>
    </div>
    <div class="toast toast-end">
      <div
        class="alert"
        :class="{
          'alert-error': alert.type === 'error',
          'alert-success': alert.type === 'success',
        }"
        v-for="alert in alerts"
      >
        <span>{{ alert.message }}</span>
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

import { AdminUsersEditableRow as EditableRow } from "#components";
const route = useRoute();
const { hasRoles } = useAuth();
const { id } = route.params;
const { user, getUser } = useAdminUsers();
await getUser(id);

const refresh = async () => {
  await getUser(id);
};

const alerts = ref([]);
</script>
