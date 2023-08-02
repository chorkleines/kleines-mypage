<template></template>

<script setup lang="ts">
definePageMeta({
    middleware: [
        "auth",
        async function (_to, _from) {
            const { user, getUser } = useAuth();
            const { isMaster, isAccountant, isCamp } = useAdmin();
            await getUser();

            if (
                !(
                    isMaster(user.value) ||
                    isAccountant(user.value) ||
                    isCamp(user.value)
                )
            ) {
                return navigateTo("/");
            }
        },
    ],
});
</script>
