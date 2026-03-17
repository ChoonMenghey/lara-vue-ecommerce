<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import UserLayout from '@/layouts/UserLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface CartItem {
    id: number;
    quantity: number;
    product: {
        id: number;
        name: string;
        price: number;
        image: string | null;
    };
}

interface Props {
    cartItems: CartItem[];
    cartCount?: number;
}

const props = withDefaults(defineProps<Props>(), {
    cartCount: 0,
});

defineExpose({
    cartCount: props.cartCount,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shopping Cart',
        href: '',
    },
];

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'MYR',
    }).format(price);
};

const placeOrder = () => {
    router.post('/order');
};
</script>

<template>
    <UserLayout :breadcrumbs="breadcrumbs" :cart-count="cartCount">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h2
                        class="mb-6 text-2xl font-bold tracking-tight text-gray-900"
                    >
                        Shopping Cart
                    </h2>
                </div>
                <div v-if="cartItems.length === 0" class="py-16 text-center">
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
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">
                        Your cart is empty
                    </h3>
                    <p class="mt-2 text-gray-500">
                        Start shopping to add items to your cart.
                    </p>
                    <Link
                        href="/"
                        class="mt-6 inline-block rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        Continue Shopping
                    </Link>
                </div>

                <div
                    v-else
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
                >
                    <div class="overflow-x-auto">
                        <Table class="w-full text-left text-sm text-gray-500">
                            <TableHeader
                                class="bg-gray-50 text-xs text-gray-700 uppercase"
                            >
                                <TableRow>
                                    <TableHead scope="col" class="px-6 py-3">
                                        Product
                                    </TableHead>
                                    <TableHead scope="col" class="px-6 py-3"
                                        >Price</TableHead
                                    >
                                    <TableHead scope="col" class="px-6 py-3">
                                        Quantity
                                    </TableHead>
                                    <TableHead scope="col" class="px-6 py-3"
                                        >Total</TableHead
                                    >
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow
                                    v-for="item in cartItems"
                                    :key="item.id"
                                    class="border-b bg-white hover:bg-gray-50"
                                >
                                    <TableCell class="px-6 py-4">
                                        <div class="flex items-center gap-4">
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
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{ item.product.name }}</span
                                            >
                                        </div>
                                    </TableCell>
                                    <TableCell
                                        class="px-6 py-4 font-medium text-gray-900"
                                    >
                                        {{ formatPrice(item.product.price) }}
                                    </TableCell>
                                    <TableCell class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="w-12 text-center font-medium"
                                                >{{ item.quantity }}</span
                                            >
                                        </div>
                                    </TableCell>
                                    <TableCell
                                        class="px-6 py-4 font-medium text-gray-900"
                                    >
                                        {{
                                            formatPrice(
                                                item.product.price *
                                                    item.quantity,
                                            )
                                        }}
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                    <div class="border-t border-gray-200 bg-gray-50 p-6">
                        <div class="flex items-center justify-between">
                            <Link
                                href="/"
                                class="font-medium text-indigo-600 hover:text-indigo-800"
                            >
                                <Button> Back </Button>
                            </Link>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">
                                    Total ({{ cartCount }} items)
                                </p>
                                <p class="text-xl font-bold text-gray-900">
                                    {{
                                        formatPrice(
                                            cartItems.reduce(
                                                (sum, item) =>
                                                    sum +
                                                    item.product.price *
                                                        item.quantity,
                                                0,
                                            ),
                                        )
                                    }}
                                </p>
                                <Button @click="placeOrder"> Pay </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
