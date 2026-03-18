<script setup lang="ts">
import { Toasts } from '@laravel-inertia-toast/vue';
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppHeader from '@/components/user/UserHeader.vue';
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
    <AppShell variant="header">
        <AppHeader :breadcrumbs="breadcrumbs" :cart-count="cartCount" />
        <AppContent variant="header">
            <slot />
            <Toasts />
        </AppContent>
    </AppShell>
</template>
