<template>
    <div class="toast toast-top toast-end" v-if="isForgotPasswordSuccessful">
        <div class="alert alert-success text-xs sm:text-sm">
            <span>
                <font-awesome-icon icon="circle-check" class="me-2" />
                パスワード再設定用のリンクが送信されました</span>
        </div>
    </div>
    <div class="bg-base-200 min-w-screen min-h-screen flex justify-center items-center">
        <div class="sm:w-96 w-full max-sm:p-5">
            <div class="mb-6">
                <h2 class="mt-2 text-center text-2xl font-bold">パスワードの再設定</h2>
                <p class="mt-3 text-center text-sm text-gray-500">
                    Kleines Mypage に登録されているメールアドレスを入力してください。
                    メールアドレス宛にパスワードの再設定用のリンクを送信します。
                </p>
            </div>

            <div class="grid gap-1">
                <div class="form-control w-full">
                    <label for="email" class="label">
                        <span class="labeltext">メールアドレス</span>
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required autofocus v-model="data.email"
                        class="input w-full input-bordered" :class="{
                            'input-bordered input-error': failedForgotPassword,
                        }" />
                    <label class="label" v-if="failedForgotPassword">
                        <span class="label-text-alt text-error">
                            {{ forgotPasswordFailureMessage }}
                        </span>
                    </label>
                </div>

                <div class="mt-5">
                    <button id="login" type="submit" class="btn btn-primary w-full" @click="submit" :class="{
                        'btn-disabled':
                            isLoadingForgotPassword || isForgotPasswordSuccessful,
                    }">
                        <span class="loading loading-spinner" v-if="isLoadingForgotPassword"></span>
                        リンクを送信
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
const {
    forgotPassword,
    isLoadingForgotPassword,
    failedForgotPassword,
    forgotPasswordFailureMessage,
    isForgotPasswordSuccessful,
} = useForgotPassword();

const data = reactive({
    email: "",
});

const submit = async () => {
    await forgotPassword(data.email);
};

definePageMeta({
    layout: "guest",
    middleware: "auth",
});
</script>
