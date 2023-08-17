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
        <li>新規団員追加</li>
      </ul>
    </div>
    <h1 class="text-2xl">新規団員追加</h1>
    <div class="max-w-lg not-prose">
      <div class="overflow-x-auto mb-3">
        <table class="table whitespace-nowrap">
          <tbody>
            <tr>
              <th class="w-36">
                <span>名前（姓）</span>
                <span class="text-error">*</span>
              </th>
              <td class="py-0">
                <input
                  type="text"
                  :value="newUser.profile.lastName"
                  @input="newUser.profile.lastName = $event.target.value"
                  class="input input-bordered input-sm w-full max-w-xs"
                  :class="{
                    'input-disabled': isLoading,
                    'input-error': formErrors.includes('profile.lastName'),
                  }"
                />
              </td>
            </tr>
            <tr>
              <th class="w-36">
                <span>名前（名）</span>
                <span class="text-error">*</span>
              </th>
              <td class="py-0">
                <input
                  type="text"
                  :value="newUser.profile.firstName"
                  @input="newUser.profile.firstName = $event.target.value"
                  class="input input-bordered input-sm w-full max-w-xs"
                  :class="{
                    'input-disabled': isLoading,
                    'input-error': formErrors.includes('profile.firstName'),
                  }"
                />
              </td>
            </tr>
            <tr>
              <th class="w-36">
                <span>名前（かな）</span>
              </th>
              <td class="py-0">
                <input
                  type="text"
                  :value="newUser.profile.nameKana"
                  @input="newUser.profile.nameKana = $event.target.value"
                  class="input input-bordered input-sm w-full max-w-xs"
                  :class="{
                    'input-disabled': isLoading,
                    'input-error': formErrors.includes('profile.nameKana'),
                  }"
                />
              </td>
            </tr>
            <tr>
              <th class="w-36">
                <span>学年</span>
                <span class="text-error">*</span>
              </th>
              <td class="py-0">
                <input
                  type="number"
                  :value="newUser.profile.grade"
                  @input="newUser.profile.grade = $event.target.value"
                  class="input input-bordered input-sm w-full max-w-xs"
                  :class="{
                    'input-disabled': isLoading,
                    'input-error': formErrors.includes('profile.grade'),
                  }"
                />
              </td>
            </tr>
            <tr>
              <th class="w-36">
                <span>パート</span>
                <span class="text-error">*</span>
              </th>
              <td class="py-0">
                <select
                  class="select select-bordered select-sm w-full max-w-xs"
                  :value="newUser.profile.part"
                  @input="newUser.profile.part = $event.target.value"
                  :class="{
                    'select-disabled': isLoading,
                    'select-error': formErrors.includes('profile.part'),
                  }"
                >
                  <option disabled selected>選択肢を選ぶ</option>
                  <option value="S">Soprano</option>
                  <option value="A">Alto</option>
                  <option value="T">Tenor</option>
                  <option value="B">Bass</option>
                </select>
              </td>
            </tr>
            <tr>
              <th class="w-36">
                <span>メールアドレス</span>
                <span class="text-error">*</span>
              </th>
              <td class="py-0">
                <input
                  type="email"
                  :value="newUser.email"
                  @input="newUser.email = $event.target.value"
                  class="input input-bordered input-sm w-full max-w-xs"
                  :class="{
                    'input-disabled': isLoading,
                    'input-error': formErrors.includes('email'),
                  }"
                />
              </td>
            </tr>
            <tr>
              <th class="w-36">
                <span>在団状況</span>
                <span class="text-error">*</span>
              </th>
              <td class="py-0">
                <select
                  class="select select-bordered select-sm w-full max-w-xs"
                  :value="newUser.status"
                  @input="newUser.status = $event.target.value"
                  :class="{
                    'select-disabled': isLoading,
                    'select-error': formErrors.includes('status'),
                  }"
                >
                  <option disabled selected>選択肢を選ぶ</option>
                  <option value="PRESENT">在団</option>
                  <option value="ABSENT">休団</option>
                  <option value="RESIGNED">退団</option>
                </select>
              </td>
            </tr>
            <tr>
              <th class="w-36">
                <span>誕生日</span>
              </th>
              <td class="py-0">
                <input
                  type="date"
                  :value="newUser.profile.birthday"
                  @input="newUser.profile.birthday = $event.target.value"
                  class="input input-bordered input-sm w-full max-w-xs"
                  :class="{
                    'input-disabled': isLoading,
                    'input-error': formErrors.includes('profile.birthday'),
                  }"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="flex justify-center">
        <button
          class="btn btn-primary btn-sm"
          @click="save()"
          :class="{ 'btn-disabled': isLoading }"
        >
          <span
            class="loading loading-spinner loading-sm"
            v-if="isLoading"
          ></span>
          追加
        </button>
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
  {{ alert }}
</template>

<script setup lang="ts">
definePageMeta({
  middleware: [
    "auth",
    async function (_to, _from) {
      const { user, getUser } = useAuth();
      const { isMaster, isManager } = useAdmin();
      await getUser();

      if (!(isMaster(user.value) || isManager(user.value))) {
        return navigateTo("/");
      }
    },
  ],
});

const { addUser } = useAdminUsers();
const router = useRouter();
const newUser = reactive({
  email: null,
  status: null,
  profile: {
    lastName: null,
    firstName: null,
    nameKana: null,
    grade: null,
    part: null,
    birthday: null,
  },
});
const alerts = ref([]);
const formErrors = ref([]);
const isLoading = ref(false);

const showAlert = async (message, type) => {
  alerts.value.push({ message, type });
  await new Promise((r) => setTimeout(r, 3000));
  const idx = alerts.value.map((e) => e.message).indexOf(message);
  if (idx > -1) {
    alerts.value.splice(idx, 1);
  }
};

const save = async () => {
  isLoading.value = true;
  formErrors.value = [];
  if (newUser.profile.lastName === null || newUser.profile.lastName === "") {
    showAlert("名前（姓）を入力してください", "error");
    formErrors.value.push("profile.lastName");
  }
  if (newUser.profile.firstName === null || newUser.profile.firstName === "") {
    showAlert("名前（名）を入力してください", "error");
    formErrors.value.push("profile.firstName");
  }
  if (newUser.profile.grade === null) {
    showAlert("学年を入力してください", "error");
    formErrors.value.push("profile.grade");
  }
  if (newUser.profile.part === null || newUser.profile.part === "") {
    showAlert("パートを入力してください", "error");
    formErrors.value.push("profile.part");
  }
  if (newUser.email === null || newUser.email === "") {
    showAlert("メールアドレスを入力してください", "error");
    formErrors.value.push("email");
  }
  if (newUser.status === null || newUser.status === "") {
    showAlert("在団状況を入力してください", "error");
    formErrors.value.push("status");
  }
  if (formErrors.value.length > 0) {
    isLoading.value = false;
    return;
  }

  const addUserBody = {
    email: newUser.email,
    status: newUser.status,
    profile: {
      last_name: newUser.profile.lastName,
      first_name: newUser.profile.firstName,
      name_kana: newUser.profile.nameKana,
      grade: newUser.profile.grade,
      part: newUser.profile.part,
      birthday: newUser.profile.birthday,
    },
  };

  const { data, status, error } = await addUser(addUserBody);
  isLoading.value = false;
  if (status.value === "success") {
    router.push(`/admin/users/${data.value.id}`);
  } else {
    for (const [key, values] of Object.entries(error.value.data.detail)) {
      for (const value of values) {
        showAlert(`${value}`, "error");
        formErrors.value.push(snakeToCamelCase(key.replace(" ", "_")));
      }
    }
  }
};

const snakeToCamelCase = (str) =>
  str.replace(/([-_][a-z])/g, (group) =>
    group.toUpperCase().replace("-", "").replace("_", ""),
  );
</script>
