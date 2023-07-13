<template>
    <h1>Welcome to the homepage</h1>
    <NuxtLink to="/about">About</NuxtLink>
    {{ user }}
</template>

<script setup lang="ts">
definePageMeta({
    middleware: "auth",
});

console.log("a");
const { user, getUser } = useAuth();
await getUser();
</script>
