<template>
  <div class="toast toast-top toast-end" v-if="isResetPasswordSuccessful">
    <div class="alert alert-success text-xs sm:text-sm">
      <font-awesome-icon icon="circle-check" />
      <div>
        パスワードの再設定に成功しました
        <br />
        {{ secondsUntilRouterPush }}秒後にホーム画面に移動します
      </div>
    </div>
  </div>
  <div
    class="bg-base-200 min-w-screen min-h-screen flex justify-center items-center"
  >
    <div class="sm:w-96 w-full max-sm:p-5">
      <div class="mb-6">
        <h2 class="mt-2 text-center text-2xl font-bold">パスワードの再設定</h2>
        <!-- <p class="mt-3 text-center text-sm text-gray-500"> -->
        <!-- </p> -->
      </div>

      <div class="grid gap-1">
        <div class="form-control w-full">
          <label for="email" class="label">
            <span class="labeltext">メールアドレス</span>
          </label>
          <input
            id="email"
            name="email"
            type="email"
            autocomplete="email"
            required
            autofocus
            v-model="data.email"
            class="input w-full input-bordered"
            :class="{
              'input-bordered input-error': failedResetPassword,
            }"
          />
        </div>
        <div class="form-control w-full">
          <label for="email" class="label">
            <span class="labeltext">パスワード</span>
          </label>
          <input
            id="password"
            name="password"
            type="password"
            required
            v-model="data.password"
            class="input w-full input-bordered"
            :class="{
              'input-bordered input-error': failedResetPassword,
            }"
          />
        </div>

        <div class="form-control w-full">
          <label for="email" class="label">
            <span class="labeltext">パスワード（確認）</span>
          </label>
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            required
            v-model="data.passwordConfirmation"
            @keyup.enter="submit"
            class="input w-full input-bordered"
            :class="{
              'input-bordered input-error': failedResetPassword,
            }"
          />
          <label class="label" v-if="failedResetPassword">
            <span class="label-text-alt text-error">
              {{ resetPasswordFailureMessage }}
            </span>
          </label>
        </div>

        <div class="mt-5">
          <button
            id="login"
            type="submit"
            class="btn btn-primary w-full"
            @click="submit"
            :class="{
              'btn-disabled':
                isLoadingResetPassword || isResetPasswordSuccessful,
            }"
          >
            <span
              class="loading loading-spinner"
              v-if="isLoadingResetPassword"
            ></span>
            パスワードを再設定
          </button>
        </div>
        <div class="mt-3">
          <NuxtLink to="/login" class="btn btn-link btn-sm w-full">
            ログイン画面に戻る
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const router = useRouter();
const {
  resetPassword,
  isLoadingResetPassword,
  failedResetPassword,
  resetPasswordFailureMessage,
  isResetPasswordSuccessful,
} = useResetPassword();

const { token } = route.params;
const data = reactive({
  email: "",
  password: "",
  passwordConfirmation: "",
});
const secondsUntilRouterPush = ref(0);

const submit = async () => {
  await resetPassword(
    data.email,
    data.password,
    data.passwordConfirmation,
    token,
  );
  if (isResetPasswordSuccessful) {
    secondsUntilRouterPush.value = 5;
    for (let i = 0; i < 5; i++) {
      await new Promise((resolve) => setTimeout(resolve, 1000));
      secondsUntilRouterPush.value--;
    }
    router.push("/");
  }
};

definePageMeta({
  layout: "guest",
  middleware: "auth",
});
</script>
