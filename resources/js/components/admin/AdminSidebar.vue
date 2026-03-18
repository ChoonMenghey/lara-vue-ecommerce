<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, ScanBarcode, Cuboid } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes/admin';

import { view } from '@/routes/admin/orders';
import { index } from '@/routes/admin/products';
import type { NavItem } from '@/types';
import NavAdmin from './NavAdmin.vue';


const mainNavItems: NavItem[] = [
    {
        title: 'DashBoard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Product',
        href: index(),
        icon: ScanBarcode,
    },
    {
        title: 'Orders',
        href: view(),
        icon: Cuboid,
    }
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavAdmin />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
