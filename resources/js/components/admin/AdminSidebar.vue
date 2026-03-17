<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, ScanBarcode, Coins } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
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
import { index } from '@/routes/admin/products';
import type { NavItem } from '@/types';


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
        title: 'Sales',
        href: dashboard(),
        icon: Coins,
    },
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
            
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
