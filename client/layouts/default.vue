<template>
  <Html data-theme="light">
    <Head>
      <Title>Kleines Mypage</Title>
    </Head>
  </Html>

  <div
    class="fixed w-screen h-screen flex justify-center items-center bg-base-200/50 top-0 left-0 z-50"
    :class="{
      hidden: !isFullScreenLoading,
    }"
  >
    <div class="animate-ping h-2 w-2 bg-primary rounded-full"></div>
    <div class="animate-ping h-2 w-2 bg-primary rounded-full mx-4"></div>
    <div class="animate-ping h-2 w-2 bg-primary rounded-full"></div>
  </div>
  <div class="bg-base-100 drawer lg:drawer-open w-screen h-screen">
    <input
      id="my-drawer-3"
      type="checkbox"
      class="drawer-toggle"
      v-model="drawer"
    />
    <div class="drawer-content flex flex-col w-screen lg:w-auto h-screen">
      <!-- Navbar -->
      <div class="navbar bg-base-200 shadow justify-between">
        <div class="flex-none">
          <label for="my-drawer-3" class="btn btn-circle btn-ghost lg:hidden">
            <font-awesome-icon icon="bars" />
          </label>
        </div>
        <div class="dropdown dropdown-end">
          <label tabindex="0" class="btn btn-ghost btn-circle avatar text-xl">
            <font-awesome-icon icon="user" />
          </label>
          <ul
            tabindex="0"
            class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52"
          >
            <li class="disabled">
              <a>{{ user.displayName }}</a>
            </li>
            <li>
              <!-- <router-link to="/logout">ログアウト</router-link> -->
              <a @click="submitLogut">ログアウト</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- End of Navbar -->
      <div class="flex-1 p-5 overflow-y-auto bg-base-200/50">
        <slot v-if="!isFullScreenLoading"></slot>
      </div>
    </div>
    <!-- Sidebar -->
    <div class="drawer-side">
      <label for="my-drawer-3" class="drawer-overlay"></label>
      <ul class="menu p-0 w-80 h-full bg-base-200">
        <li>
          <NuxtLink
            aria-current="page"
            to="/"
            class="btn btn-ghost normal-case text-xl rounded-none flex content-center"
            style="min-height: 4rem"
            @click="drawer = false"
          >
            Kleines Mypage</NuxtLink
          >
        </li>
        <li
          class="border-l-4"
          :class="{
            'border-primary-content': route.path !== '/',
            'border-primary': route.path === '/',
          }"
        >
          <NuxtLink
            aria-current="page"
            to="/"
            class="rounded-none p-4"
            @click="drawer = false"
          >
            <font-awesome-icon icon="home" class="me-2" />ホーム
          </NuxtLink>
        </li>
        <li
          class="border-l-4"
          :class="{
            'border-primary-content': route.path !== '/users',
            'border-primary': route.path === '/users',
          }"
        >
          <NuxtLink
            to="/users"
            class="rounded-none p-4"
            @click="drawer = false"
          >
            <font-awesome-icon icon="users" class="me-2" />
            団員リスト
          </NuxtLink>
        </li>
        <li
          class="border-l-4"
          :class="{
            'border-primary-content': !route.path.startsWith('/accountings'),
            'border-primary': route.path.startsWith('/accountings'),
          }"
        >
          <NuxtLink
            to="/accountings"
            class="rounded-none p-4"
            @click="drawer = false"
          >
            <font-awesome-icon icon="yen-sign" class="me-2" />
            集金リスト
          </NuxtLink>
        </li>
        <li
          class="border-l-4"
          :class="{
            'border-primary-content': route.path !== '/individual_accountings',
            'border-primary': route.path === '/individual_accountings',
          }"
        >
          <NuxtLink
            to="/individual_accountings"
            class="rounded-none p-4"
            @click="drawer = false"
          >
            <font-awesome-icon icon="wallet" class="me-2" />
            個別会計
          </NuxtLink>
        </li>
      </ul>
    </div>
    <!-- End of Sidebar -->
  </div>
</template>

<script setup lang="ts">
const isFullScreenLoading = ref(false);
const { user, getUser, logout } = useAuth();
const route = useRoute();
const router = useRouter();
const drawer = ref(false);
await getUser();

const submitLogut = () => {
  logout();
  router.push("/login");
};
</script>
