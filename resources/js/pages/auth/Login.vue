<template>
    <div
        class="bg-base-200 min-w-screen min-h-screen flex justify-center items-center"
    >
        <div class="sm:w-96 w-full max-sm:p-5">
            <div class="mb-3">
                <img
                    class="mx-auto h-20 w-auto"
                    src="https://www.chorkleines.com/logo.png"
                    alt="Chor Kleines Logo"
                />
                <h2 class="mt-2 text-center text-2xl font-bold">
                    Kleines Mypage
                </h2>
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
                        v-model="email"
                        class="input w-full input-bordered"
                        :class="{
                            'input-bordered input-error': failedLogin,
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
                        autocomplete="current-password"
                        required
                        v-model="password"
                        @keyup.enter="getBearerToken"
                        class="input w-full input-bordered"
                        :class="{
                            'input-bordered input-error': failedLogin,
                        }"
                    />
                    <label class="label" v-if="failedLogin">
                        <span class="label-text-alt text-error">
                            {{ loginFailureMessage }}
                        </span>
                    </label>
                </div>

                <div class="mt-5">
                    <button
                        type="submit"
                        class="btn btn-primary w-full"
                        @click="getBearerToken"
                        :class="{
                            loading: isLoadingLogin,
                        }"
                    >
                        ログイン
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import AuthApiService from "@/services/AuthApiService";
import { LoginRequest, BearerTokenResponse } from "@/types/AuthType";

export default defineComponent({
    data() {
        return {
            email: "",
            password: "",
            bearerToken: "",
            isLoadingLogin: false,
            loginFailureMessage: "",
            failedLogin: false,
            appName: import.meta.env.VITE_APP_NAME,
        };
    },
    methods: {
        getBearerToken() {
            this.isLoadingLogin = true;
            this.failedLogin = false;
            let data: LoginRequest = {
                email: this.email,
                password: this.password,
            };
            AuthApiService.getBearerToken(data)
                .then((response: BearerTokenResponse) => {
                    this.bearerToken = response.data.access_token;
                    localStorage.setItem("jwt", "Bearer " + this.bearerToken);
                    this.$router.push("/");
                })
                .catch((e: Error) => {
                    console.log(e);
                    this.failedLogin = true;
                    this.loginFailureMessage = e.message;
                    if (e.response.status === 401) {
                        this.loginFailureMessage = "ログイン情報が誤っています";
                    }
                    this.isLoadingLogin = false;
                });
        },
    },
});
</script>

<style>
#email {
    margin-bottom: 0;
}
</style>
