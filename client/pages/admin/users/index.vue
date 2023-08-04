<template></template>

<script setup lang="ts">
definePageMeta({
    middleware: [
        "auth",
        async function (_to, _from) {
            const { user, getUser } = useAuth();
            const { isAdmin } = useAdmin();
            await getUser();

            if (!isAdmin(user.value)) {
                return navigateTo("/");
            }
        },
    ],
});
</script>
