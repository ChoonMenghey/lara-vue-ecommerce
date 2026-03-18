<script setup lang="ts">
import { Toasts } from '@laravel-inertia-toast/vue';
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description">
        <slot />
        <Toasts />
    </AuthLayout>
</template>
