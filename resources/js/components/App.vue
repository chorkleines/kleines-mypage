<template>
    <div class="bg-base-100 drawer drawer-mobile">
        <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-base-200 shadow justify-between">
                <div class="flex-none">
                    <label
                        for="my-drawer-3"
                        class="btn btn-circle btn-ghost lg:hidden"
                    >
                        <i class="fa-solid fa-bars"></i>
                    </label>
                </div>
                <div class="dropdown dropdown-end">
                    <label
                        tabindex="0"
                        class="btn btn-ghost btn-circle avatar text-xl"
                    >
                        <i class="fa-solid fa-user"></i>
                    </label>
                    <ul
                        tabindex="0"
                        class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52"
                    >
                        <li class="disabled">
                            <a>
                                {{
                                    `${loginUser.grade}${loginUser.part} ${loginUser.lastName}${loginUser.firstName}`
                                }}
                            </a>
                        </li>
                        <li>
                            <router-link to="/logout">ログアウト</router-link>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- End of Navbar -->
            <div class="flex-1 p-5 overflow-y-auto bg-base-100">
                <div
                    class="fixed w-screen h-screen flex justify-center items-center bg-base-200/90 top-0 left-0 z-50"
                    :class="{
                        hidden: !isFullScreenLoading,
                    }"
                >
                    <div
                        class="animate-ping h-2 w-2 bg-primary rounded-full"
                    ></div>
                    <div
                        class="animate-ping h-2 w-2 bg-primary rounded-full mx-4"
                    ></div>
                    <div
                        class="animate-ping h-2 w-2 bg-primary rounded-full"
                    ></div>
                </div>
                <slot name="content"></slot>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="drawer-side">
            <label for="my-drawer-3" class="drawer-overlay"></label>
            <ul class="menu pt-0 w-80 bg-base-200">
                <li>
                    <router-link
                        to="/"
                        class="btn btn-ghost normal-case text-xl"
                        style="min-height: 4rem"
                        >{{ appName }}</router-link
                    >
                </li>
                <li :class="{ bordered: route.name.match('home') }">
                    <router-link :to="{ name: 'home' }"
                        ><i class="fa-solid fa-house me-2"></i
                        >ホーム</router-link
                    >
                </li>
                <li :class="{ bordered: route.name.match('users') }">
                    <router-link :to="{ name: 'users' }"
                        ><i class="fa-solid fa-users me-2"></i
                        >団員リスト</router-link
                    >
                </li>
            </ul>
        </div>
        <!-- End of Sidebar -->
    </div>
</template>

<script lang="ts">
import { reactive, defineComponent } from "vue";
import AuthApiService from "@/services/AuthApiService";
import { LoginUserResponse, LoginUser } from "@/types/AuthType";
import { useRoute } from "vue-router";

export default defineComponent({
    props: {
        isFullScreenLoading: Boolean,
    },
    setup() {
        const loginUser = reactive<LoginUser>({
            firstName: "",
            lastName: "",
            grade: null,
            part: "",
        });

        const route = useRoute();
        const appName = import.meta.env.VITE_APP_NAME;

        return { loginUser, route, appName };
    },
    methods: {
        getLoginUser() {
            AuthApiService.getLoginUser()
                .then((response: LoginUserResponse) => {
                    this.loginUser.firstName = response.data.first_name;
                    this.loginUser.lastName = response.data.last_name;
                    this.loginUser.grade = response.data.grade;
                    this.loginUser.part = response.data.part;
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
