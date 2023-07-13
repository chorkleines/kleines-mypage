<template>
    <section>
        <p>login required</p>
        <form @submit.prevent="submit">
            <!-- Email Address -->
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" class="block mt-1 w-full" v-model="data.email" required autoFocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password">Password</label>
                <input id="password" type="password" class="block mt-1 w-full" v-model="data.password" required
                    autoComplete="current-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button class="ml-3" @click="submit">Login</button>
            </div>
        </form>
    </section>
</template>

<script setup lang="ts">
const { login, getUser } = useAuth();
const router = useRouter();

const data = reactive({
    email: "",
    password: "",
});

const submit = async () => {
    await login(data.email, data.password);
    await getUser();
    router.push("/");
};
</script>
