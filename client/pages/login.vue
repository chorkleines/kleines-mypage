<template>
    <div class="bg-base-200 min-w-screen min-h-screen flex justify-center items-center">
        <div class="sm:w-96 w-full max-sm:p-5">
            <div class="mb-3">
                <img class="mx-auto h-20 w-auto" src="https://www.chorkleines.com/logo.png" alt="Chor Kleines Logo" />
                <h2 class="mt-2 text-center text-2xl font-bold">
                    Kleines Mypage
                </h2>
            </div>

            <div class="grid gap-1">
                <div class="form-control w-full">
                    <label for="email" class="label">
                        <span class="labeltext">メールアドレス</span>
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required autofocus v-model="data.email"
                        class="input w-full input-bordered" :class="{
                            'input-bordered input-error': failedLogin,
                        }" />
                </div>

                <div class="form-control w-full">
                    <label for="email" class="label">
                        <span class="labeltext">パスワード</span>
                    </label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        v-model="data.password" @keyup.enter="submit" class="input w-full input-bordered" :class="{
                            'input-bordered input-error': failedLogin,
                        }" />
                    <label class="label" v-if="failedLogin">
                        <span class="label-text-alt text-error">
                            {{ loginFailureMessage }}
                        </span>
                    </label>
                </div>

                <div class="mt-5">
                    <button type="submit" class="btn btn-primary w-full" @click="submit" :class="{
                        'btn-disabled': isLoadingLogin,
                    }">
                        <span class="loading loading-spinner" v-if="isLoadingLogin"></span>
                        ログイン
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
const { login, failedLogin, loginFailureMessage, isLoadingLogin } = useAuth();
const router = useRouter();

const data = reactive({
    email: "",
    password: "",
});

const submit = async () => {
    await login(data.email, data.password);
    router.push("/");
};

definePageMeta({
    layout: "guest",
    middleware: "auth",
});
</script>
