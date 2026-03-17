<script setup lang="ts">
import { Toaster } from 'vue-sonner';
import UserLayout from '@/layouts/app/UserHeaderLayout.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
    cartCount?: number;
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    cartCount: 0,
});
</script>

<template>
    <UserLayout :breadcrumbs="breadcrumbs" :cart-count="cartCount">
        <slot />
    </UserLayout>
</template>
