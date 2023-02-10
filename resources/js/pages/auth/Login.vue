<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <router-link to="/" class="navbar-brand">{{ appName }}</router-link>
        </div>
    </nav>
    <div class="container py-4">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Login</div>

                            <div class="card-body">
                                <div class="row mb-3">
                                    <label
                                        for="email"
                                        class="col-md-4 col-form-label text-md-end"
                                        >Email Address</label
                                    >
                                    <div class="col-md-6">
                                        <input
                                            id="email"
                                            type="email"
                                            class="form-control"
                                            required
                                            :class="{
                                                'is-invalid': failedLogin,
                                            }"
                                            autocomplete="email"
                                            autofocus
                                            v-model="email"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label
                                        for="password"
                                        class="col-md-4 col-form-label text-md-end"
                                        >Password</label
                                    >
                                    <div class="col-md-6">
                                        <input
                                            id="password"
                                            type="password"
                                            class="form-control"
                                            required
                                            :class="{
                                                'is-invalid': failedLogin,
                                            }"
                                            autocomplete="current-password"
                                            v-model="password"
                                            @keyup.enter="getBearerToken"
                                        />
                                        <span
                                            class="invalid-feedback"
                                            role="alert"
                                        >
                                            <strong>{{
                                                loginFailureMessage
                                            }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <!-- <div class="row mb-3"> -->
                                <!--     <div class="col-md-6 offset-md-4"> -->
                                <!--         <div class="form-check"> -->
                                <!--             <input class="form-check-input" type="checkbox" name="remember" -->
                                <!--                 id="remember" /> -->
                                <!--             <label class="form-check-label" for="remember"> -->
                                <!--                 Remember Me -->
                                <!--             </label> -->
                                <!--         </div> -->
                                <!--     </div> -->
                                <!-- </div> -->

                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button
                                            class="btn btn-primary"
                                            @click="getBearerToken"
                                            v-bind:disabled="isLoadingLogin"
                                        >
                                            <span
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                                aria-hidden="true"
                                                v-if="isLoadingLogin"
                                            ></span>
                                            Login
                                        </button>
                                        <!-- <a class="btn btn-link" href=""> -->
                                        <!--     Forgot Your Password? -->
                                        <!-- </a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { useRouter } from "vue-router";
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
