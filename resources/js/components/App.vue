<template>
    <nav
        class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top"
    >
        <div class="container-fluid">
            <router-link class="navbar-brand" to="/">
                Kleines Mypage
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
                                aria-current="page"
                                to="/"
                            >
                                <span class="me-2"
                                    ><i class="fa-solid fa-house me-2"></i
                                    >Home</span
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
                                    >{{ loginUser.email }}</span
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
                <slot name="content"></slot>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { reactive, defineComponent } from "vue";
import AuthApiService from "@/services/AuthApiService";
import { LoginUserResponse, LoginUser } from "@/types/AuthType";

export default defineComponent({
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
