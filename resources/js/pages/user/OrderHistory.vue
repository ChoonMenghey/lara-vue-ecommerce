<script setup lang="ts">
import { computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { usePage } from '@inertiajs/vue3';
import UserLayout from '@/layouts/UserLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { home } from '@/routes';

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
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                        Order History
                    </h2>
                    <Link
                        :href="home()"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                    >
                        Continue Shopping
                    </Link>
                </div>

                <div v-if="orders.length === 0" class="py-16 text-center">
                    <svg
                        class="mx-auto h-12 w-12 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                        />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">
                        No orders yet
                    </h3>
                    <p class="mt-2 text-gray-500">
                        You haven't placed any orders yet.
                    </p>
                    <Link
                        :href=home()
                        class="mt-6 inline-block rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        Start Shopping
                    </Link>
                </div>

                <div v-else class="space-y-6">
                    <div
                        v-for="order in orders"
                        :key="order.id"
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
                    >
                        <div
                            class="border-b border-gray-200 bg-gray-50 px-6 py-4"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Order ID
                                    </p>
                                    <p
                                        class="text-lg font-semibold text-gray-900"
                                    >
                                        #{{ order.id }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Date
                                    </p>
                                    <p class="text-sm text-gray-900">
                                        {{ formatDate(order.created_at) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Total
                                    </p>
                                    <p
                                        class="text-lg font-bold text-indigo-600"
                                    >
                                        {{ formatPrice(order.total_price) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <h4 class="mb-4 text-sm font-medium text-gray-700">
                                Items
                            </h4>
                            <div class="space-y-4">
                                <div
                                    v-for="item in order.order_items"
                                    :key="item.id"
                                    class="flex items-center gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0"
                                >
                                    <div
                                        class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200"
                                    >
                                        <img
                                            v-if="item.product.image"
                                            :src="`/storage/${item.product.image}`"
                                            :alt="item.product.name"
                                            class="h-full w-full object-cover object-center"
                                        />
                                        <div
                                            v-else
                                            class="flex h-full w-full items-center justify-center bg-gray-100"
                                        >
                                            <svg
                                                class="h-8 w-8 text-gray-300"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">
                                            {{ item.product.name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ item.product.type }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">
                                            Qty: {{ item.quantity }}
                                        </p>
                                        <p class="font-medium text-gray-900">
                                            {{ formatPrice(item.unit_price) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
