<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <router-link to="/" class="navbar-brand">Kleines Mypage</router-link>
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
                                    <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" required
                                            autocomplete="email" autofocus v-model="email" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password"
                                            required autocomplete="current-password" v-model="password" />
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
                                        <button class="btn btn-primary" @click="getBearerToken">
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
        };
    },
    methods: {
        getBearerToken() {
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
                });
        },
    },
});
</script>
