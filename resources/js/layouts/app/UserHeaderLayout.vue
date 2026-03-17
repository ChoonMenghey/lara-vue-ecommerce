<script setup lang="ts">
import { Toaster } from 'vue-sonner';
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
        </AppContent>
    </AppShell>
</template>
