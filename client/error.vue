<template>
    <div class="bg-base-200 min-w-screen min-h-screen flex justify-center items-center">
        <div class="flex justify-center items-center h-full flex-col gap-5 prose mx-auto">
            <div class="text-6xl font-bold">{{ error?.statusCode }}</div>
            <div class="">{{ error?.message }}</div>
            <div class="not-prose">
                <a class="btn" href="/"> ホームに戻る </a>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
const error = useError();
</script>
