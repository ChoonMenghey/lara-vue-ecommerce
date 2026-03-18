<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { toast } from 'vue-sonner';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AdminLayout.vue';

import { view } from '@/routes/admin/orders';
import type { BreadcrumbItem } from '@/types';

interface OrderItem {
    id: number;
    product_id: number;
    quantity: number;
    unit_price: number;
    product: {
        id: number;
        name: string;
        price: number;
        image: string | null;
    };
}

interface Order {
    id: number;
    user_id: number;
    total_price: number;
    order_status: string;
    created_at: string;
    user: {
        id: number;
        name: string;
        email: string;
    };
    order_items: OrderItem[];
}

interface Props {
    orders: {
        data: Order[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Orders',
        href: view(),
    },
];

const page = usePage();
const flash = computed(
    () =>
        page.props.flash as { success?: string | null; error?: string | null },
);

watch(
    () => flash.value.success,
    (newVal) => {
        if (newVal) {
            toast.success(newVal, {
                duration: 3000,
            });
        }
    },
    { immediate: true },
);

watch(
    () => flash.value.error,
    (newVal) => {
        if (newVal) {
            toast.error(newVal, {
                duration: 3000,
            });
        }
    },
    { immediate: true },
);

const pendingOrders = computed(() =>
    props.orders.data.filter((order) => order.order_status === 'Pending'),
);

const successOrders = computed(() =>
    props.orders.data.filter((order) => order.order_status === 'Success'),
);

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'MYR',
    }).format(price);
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const handleUpdateStatus = (orderId: number) => {
    router.put(
        `/admin/orders/${orderId}`,
        {},
        {
            onSuccess: () => {
                router.reload();
            },
        },
    );
};
</script>

<template>
    <Head title="Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1 class="text-2xl font-bold">Orders Management</h1>

            <div class="space-y-8">
                <div>
                    <h2 class="mb-4 text-xl font-semibold text-orange-600">
                        Pending Orders
                    </h2>
                    <div
                        v-if="pendingOrders.length === 0"
                        class="rounded-lg border p-8 text-center text-muted-foreground"
                    >
                        No pending orders
                    </div>
                    <Table v-else>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Order ID</TableHead>
                                <TableHead>Customer</TableHead>
                                <TableHead>Total Price</TableHead>
                                <TableHead>Date</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center"
                                    >Action</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="order in pendingOrders"
                                :key="order.id"
                            >
                                <TableCell class="font-medium"
                                    >#{{ order.id }}</TableCell
                                >
                                <TableCell>
                                    <div>
                                        <p class="font-medium">
                                            {{ order.user.name }}
                                        </p>
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{ order.user.email }}
                                        </p>
                                    </div>
                                </TableCell>
                                <TableCell>{{
                                    formatPrice(order.total_price)
                                }}</TableCell>
                                <TableCell>{{
                                    formatDate(order.created_at)
                                }}</TableCell>
                                <TableCell>
                                    <Badge variant="secondary">{{
                                        order.order_status
                                    }}</Badge>
                                </TableCell>
                                <TableCell class="text-center">
                                    <Button
                                        @click="handleUpdateStatus(order.id)"
                                        class="bg-green-600 hover:bg-green-700"
                                    >
                                        Mark as Success
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div>
                    <h2 class="mb-4 text-xl font-semibold text-green-600">
                        Success Orders
                    </h2>
                    <div
                        v-if="successOrders.length === 0"
                        class="rounded-lg border p-8 text-center text-muted-foreground"
                    >
                        No success orders
                    </div>
                    <Table v-else>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Order ID</TableHead>
                                <TableHead>Customer</TableHead>
                                <TableHead>Total Price</TableHead>
                                <TableHead>Date</TableHead>
                                <TableHead>Status</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="order in successOrders"
                                :key="order.id"
                            >
                                <TableCell class="font-medium"
                                    >#{{ order.id }}</TableCell
                                >
                                <TableCell>
                                    <div>
                                        <p class="font-medium">
                                            {{ order.user.name }}
                                        </p>
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{ order.user.email }}
                                        </p>
                                    </div>
                                </TableCell>
                                <TableCell>{{
                                    formatPrice(order.total_price)
                                }}</TableCell>
                                <TableCell>{{
                                    formatDate(order.created_at)
                                }}</TableCell>
                                <TableCell>
                                    <Badge
                                        variant="default"
                                        class="bg-green-600"
                                        >{{ order.order_status }}</Badge
                                    >
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
