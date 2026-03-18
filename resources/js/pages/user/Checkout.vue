<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ShoppingCart, Package, CreditCard, ArrowLeft } from 'lucide-vue-next';
import Button from '@/components/ui/button/Button.vue';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
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
                        class="mb-6 text-2xl font-bold tracking-tight text-foreground"
                    >
                        Checkout
                    </h2>
                </div>
                <div v-if="cartItems.length === 0" class="py-16 text-center">
                    <div
                        class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-muted"
                    >
                        <ShoppingCart class="h-10 w-10 text-muted-foreground" />
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-foreground">
                        Your cart is empty
                    </h3>
                    <p class="mb-6 text-muted-foreground">
                        Start shopping to add items to your cart.
                    </p>
                    <Button variant="outline" as-child>
                        <Link href="/">Continue Shopping</Link>
                    </Button>
                </div>

                <div v-else class="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <ShoppingCart class="h-5 w-5" />
                                Order Summary ({{ cartCount }} items)
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-0">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Product</TableHead>
                                        <TableHead>Price</TableHead>
                                        <TableHead>Quantity</TableHead>
                                        <TableHead>Total</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="item in cartItems"
                                        :key="item.id"
                                    >
                                        <TableCell>
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <div
                                                    class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md bg-muted"
                                                >
                                                    <img
                                                        v-if="
                                                            item.product.image
                                                        "
                                                        :src="`/storage/${item.product.image}`"
                                                        :alt="item.product.name"
                                                        class="h-full w-full object-cover"
                                                    />
                                                    <div
                                                        v-else
                                                        class="flex h-full w-full items-center justify-center"
                                                    >
                                                        <Package
                                                            class="h-8 w-8 text-muted-foreground"
                                                        />
                                                    </div>
                                                </div>
                                                <span class="font-medium">{{
                                                    item.product.name
                                                }}</span>
                                            </div>
                                        </TableCell>
                                        <TableCell>{{
                                            formatPrice(item.product.price)
                                        }}</TableCell>
                                        <TableCell>
                                            <span class="font-medium">{{
                                                item.quantity
                                            }}</span>
                                        </TableCell>
                                        <TableCell class="font-medium">
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
                        </CardContent>
                        <CardFooter
                            class="flex items-center justify-between border-t p-6"
                        >
                            <Button variant="outline" as-child>
                                <Link href="/" class="gap-2">
                                    <ArrowLeft class="h-4 w-4" />
                                    Back to Shop
                                </Link>
                            </Button>
                            <div class="text-right">
                                <p class="text-sm text-muted-foreground">
                                    Total Amount
                                </p>
                                <p class="text-2xl font-bold text-primary">
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
                            </div>
                        </CardFooter>
                    </Card>

                    <div class="flex justify-end">
                        <Button size="lg" class="gap-2" @click="placeOrder">
                            <CreditCard class="h-5 w-5" />
                            Place Order
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
