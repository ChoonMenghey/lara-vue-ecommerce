<script setup lang="ts">
import { computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { usePage } from '@inertiajs/vue3';
import UserLayout from '@/layouts/UserLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { home } from '@/routes';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import { Package, Calendar, ArrowRight, ShoppingBag } from 'lucide-vue-next';

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
        type: string;
    };
}

interface Order {
    id: number;
    user_id: number;
    total_price: number;
    order_status: string;
    created_at: string;
    order_items: OrderItem[];
}

interface Props {
    orders: Order[];
    cartCount?: number;
}

const props = withDefaults(defineProps<Props>(), {
    cartCount: 0,
});

const page = usePage();
const flash = computed(() => page.props.flash as { success?: string | null });

const ordersWithDisplayId = computed(() => {
    return props.orders.map((order, index) => ({
        ...order,
        displayId: index + 1,
    }));
});

watch(
    () => flash.value.success,
    (newVal) => {
        if (newVal) {
            toast.success(newVal, {
                duration: 3000,
                style: {
                    background: '#22c55e',
                    color: '#ffffff',
                },
            });
        }
    },
    { immediate: true },
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Dashboard',
        href: home(),
    },
    {
        title: 'Order History',
        href: '/order/history',
    },
];

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
</script>

<template>
    <UserLayout :breadcrumbs="breadcrumbs" :cart-count="cartCount">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="mx-auto w-4/5 px-4 py-8 sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2
                        class="text-2xl font-bold tracking-tight text-foreground"
                    >
                        Order History
                    </h2>
                    <Button variant="outline" as-child>
                        <Link :href="home()" class="gap-2">
                            Continue Shopping
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                    </Button>
                </div>

                <div v-if="orders.length === 0" class="py-16 text-center">
                    <div
                        class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-muted"
                    >
                        <ShoppingBag class="h-10 w-10 text-muted-foreground" />
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-foreground">
                        No orders yet
                    </h3>
                    <p class="mb-6 text-muted-foreground">
                        You haven't placed any orders yet.
                    </p>
                    <Button as-child>
                        <Link :href="home()">Start Shopping</Link>
                    </Button>
                </div>

                <div v-else class="space-y-6">
                    <Card v-for="order in ordersWithDisplayId" :key="order.id">
                        <CardHeader class="pb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            Order ID
                                        </p>
                                        <CardTitle class="text-lg"
                                            >#{{ order.displayId }}</CardTitle
                                        >
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <p
                                            class="flex items-center gap-1 text-sm text-muted-foreground"
                                        >
                                            <Calendar class="h-3 w-3" />
                                            Date
                                        </p>
                                        <p class="text-sm font-medium">
                                            {{ formatDate(order.created_at) }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            Total
                                        </p>
                                        <p
                                            class="text-xl font-bold text-primary"
                                        >
                                            {{ formatPrice(order.total_price) }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            Status
                                        </p>
                                        <Badge
                                            :variant="
                                                order.order_status === 'Success'
                                                    ? 'default'
                                                    : 'secondary'
                                            "
                                            class="mt-1"
                                        >
                                            {{ order.order_status }}
                                        </Badge>
                                    </div>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <p
                                class="mb-3 text-sm font-medium text-muted-foreground"
                            >
                                Items
                            </p>
                            <div class="space-y-3">
                                <div
                                    v-for="item in order.order_items"
                                    :key="item.id"
                                    class="flex items-center gap-4 rounded-lg border p-3"
                                >
                                    <div
                                        class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-md bg-muted"
                                    >
                                        <img
                                            v-if="item.product.image"
                                            :src="`/storage/${item.product.image}`"
                                            :alt="item.product.name"
                                            class="h-full w-full object-cover"
                                        />
                                        <div
                                            v-else
                                            class="flex h-full w-full items-center justify-center"
                                        >
                                            <Package
                                                class="h-6 w-6 text-muted-foreground"
                                            />
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium">
                                            {{ item.product.name }}
                                        </p>
                                        <Badge
                                            variant="outline"
                                            class="mt-1 text-xs"
                                            >{{ item.product.type }}</Badge
                                        >
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            Qty: {{ item.quantity }}
                                        </p>
                                        <p class="font-medium">
                                            {{ formatPrice(item.unit_price) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
