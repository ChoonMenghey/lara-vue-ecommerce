<script setup lang="ts">
import { Toasts } from '@laravel-inertia-toast/vue';
import AppSidebar from '@/components/admin/AdminSidebar.vue';
import AppSidebarHeader from '@/components/admin/AdminSidebarHeader.vue';
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';

import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
            <Toasts />
        </AppContent>
    </AppShell>
</template>
