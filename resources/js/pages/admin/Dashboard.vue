<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableHeader,
    TableRow,
    TableHead,
    TableCell,
} from '@/components/ui/table';
import Badge from '@/components/ui/badge/Badge.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { dashboard } from '@/routes/admin';
import type { BreadcrumbItem } from '@/types';
import {
    Users,
    ShoppingCart,
    DollarSign,
    TrendingUp,
    Package,
    ChartBar,
} from 'lucide-vue-next';

const page = usePage();
const stats = computed(
    () =>
        page.props.stats as {
            totalUsers: number;
            totalProductsSold: number;
            totalSalesLatestMonth: number;
            productsSold: { id: number; name: string; quantity_sold: number }[];
            salesByType: { type: string; quantity_sold: number }[];
        },
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin Dashboard',
        href: dashboard(),
    },
];

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(value);
};

const getMaxQuantity = (arr: { quantity_sold: number }[]) => {
    return Math.max(...arr.map((item) => item.quantity_sold), 1);
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card class="overflow-hidden border-l-4 border-l-primary">
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Total Users</CardTitle
                        >
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">
                            {{ stats.totalUsers }}
                        </div>
                        <p class="mt-1 text-xs text-muted-foreground">
                            Registered users
                        </p>
                    </CardContent>
                </Card>

                <Card class="overflow-hidden border-l-4 border-l-blue-500">
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Products Sold</CardTitle
                        >
                        <ShoppingCart class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">
                            {{ stats.totalProductsSold }}
                        </div>
                        <p class="mt-1 text-xs text-muted-foreground">
                            Total units sold
                        </p>
                    </CardContent>
                </Card>

                <Card class="overflow-hidden border-l-4 border-l-green-500">
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Sales (Latest Month)</CardTitle
                        >
                        <DollarSign class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">
                            {{ formatCurrency(stats.totalSalesLatestMonth) }}
                        </div>
                        <p class="mt-1 text-xs text-muted-foreground">
                            Revenue this month
                        </p>
                    </CardContent>
                </Card>

                <Card class="overflow-hidden border-l-4 border-l-orange-500">
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Sales by Type</CardTitle
                        >
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div
                                v-for="typeSale in stats.salesByType"
                                :key="typeSale.type"
                                class="space-y-1"
                            >
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="font-medium capitalize">{{
                                        typeSale.type
                                    }}</span>
                                    <span class="text-muted-foreground">{{
                                        typeSale.quantity_sold
                                    }}</span>
                                </div>
                                <div
                                    class="h-2 w-full overflow-hidden rounded-full bg-secondary"
                                >
                                    <div
                                        class="h-full rounded-full bg-primary"
                                        :style="{
                                            width: `${(typeSale.quantity_sold / getMaxQuantity(stats.salesByType)) * 100}%`,
                                        }"
                                    ></div>
                                </div>
                            </div>
                            <p
                                v-if="stats.salesByType.length === 0"
                                class="py-2 text-center text-sm text-muted-foreground"
                            >
                                No data available
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <ChartBar class="h-5 w-5" />
                        Product Sales Details
                    </CardTitle>
                    <CardDescription
                        >Detailed breakdown of products sold</CardDescription
                    >
                </CardHeader>
                <CardContent>
                    <Table v-if="stats.productsSold.length > 0">
                        <TableHeader>
                            <TableRow>
                                <TableHead>Product</TableHead>
                                <TableHead class="text-right"
                                    >Quantity Sold</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="product in stats.productsSold"
                                :key="product.id"
                            >
                                <TableCell class="font-medium">
                                    <div class="flex items-center gap-2">
                                        <Package
                                            class="h-4 w-4 text-muted-foreground"
                                        />
                                        {{ product.name }}
                                    </div>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Badge variant="secondary"
                                        >{{
                                            product.quantity_sold
                                        }}
                                        units</Badge
                                    >
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div
                        v-else
                        class="flex flex-col items-center justify-center py-12 text-center"
                    >
                        <Package class="mb-4 h-12 w-12 text-muted-foreground" />
                        <p class="text-muted-foreground">
                            No sales data available
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
