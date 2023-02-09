<template>
    <App>
        <template v-slot:content>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Example Component</div>
                            <div class="card-body">
                                I'm an example component.
                            </div>
                        </div>
                        {{ loginUser.user_id }}
                        {{ loginUser.email }}
                        {{ loginUser.status }}
                        <router-link to="/logout">Logout</router-link>
                    </div>
                </div>
            </div>
        </template>
    </App>
</template>

<script lang="ts">
import { reactive, defineComponent } from "vue";
import AuthApiService from "@/services/AuthApiService";
import { LoginUserResponse, LoginUser } from "@/types/AuthType";
import App from "@/components/App.vue";

export default defineComponent({
    components: {
        App,
    },
    setup() {
        const loginUser = reactive<LoginUser>({
            user_id: null,
            email: "",
            status: "",
        });

        return { loginUser };
    },
    methods: {
        getLoginUser() {
            AuthApiService.getLoginUser()
                .then((response: LoginUserResponse) => {
                    this.loginUser.user_id = response.data.user_id;
                    this.loginUser.email = response.data.email;
                    this.loginUser.status = response.data.status;
                })
                .catch((e: Error) => {
                    console.log(e);
                });
        },
    },
    mounted() {
        this.getLoginUser();
    },
});
</script>
