<template>
    <nav
        class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top"
    >
        <div class="container-fluid">
            <router-link class="navbar-brand" to="/">
                {{ appName }}
            </router-link>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sidebar"
                aria-controls="sidebar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </nav>

    <div class="container-fluid vh-100">
        <div class="row h-100">
            <nav
                id="sidebar"
                class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse"
            >
                <div class="position-sticky h-100 d-flex flex-column">
                    <ul class="nav flex-column mt-3">
                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :class="{ active: route.name.match('home') }"
                                aria-current="page"
                                :to="{ name: 'home' }"
                            >
                                <span class="me-2"
                                    ><i class="fa-solid fa-house me-2"></i
                                    >Home</span
                                >
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :class="{ active: route.name.match('users') }"
                                aria-current="page"
                                :to="{ name: 'users' }"
                            >
                                <span class="me-2"
                                    ><i class="fa-solid fa-users me-2"></i
                                    >Users</span
                                >
                            </router-link>
                        </li>
                    </ul>
                    <ul class="nav flex-column mt-auto mb-3">
                        <li class="nav-item dropup">
                            <a
                                class="nav-link dropdown-toggle"
                                href="#"
                                id="sidebarDropdownMenuLink"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <span class="me-2"
                                    ><i class="fa-solid fa-user me-2"></i
                                    >{{
                                        `${loginUser.grade}${loginUser.part} ${loginUser.lastName}${loginUser.firstName}`
                                    }}</span
                                >
                            </a>
                            <ul
                                class="dropdown-menu"
                                aria-labelledby="sidebarDropdownMenuLink"
                            >
                                <li>
                                    <router-link
                                        class="dropdown-item"
                                        to="/logout"
                                        >Logout</router-link
                                    >
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="col-md-9 ms-sm-auto col-lg-10 px-0 py-4 mt-5">
                <div
                    id="spinner-div"
                    class="d-flex align-items-center justify-content-center"
                    :class="{
                        'd-block': isFullScreenLoading,
                        'd-none': !isFullScreenLoading,
                    }"
                >
                    <div
                        class="spinner-border text-primary"
                        role="status"
                    ></div>
                </div>
                <slot name="content"></slot>
            </div>
        </div>
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

<style>
#spinner-div {
    position: fixed;
    display: none;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: rgba(255, 255, 255, 0.9);
    z-index: 2;
}

#spinner-div > div {
    width: 3rem;
    height: 3rem;
}

#sidebar {
    position: fixed;
    overflow-y: auto;
}
</style>
