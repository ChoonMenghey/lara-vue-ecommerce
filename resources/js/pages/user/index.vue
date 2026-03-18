<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { toast } from 'vue-sonner';
import { productView } from '@/actions/App/Http/Controllers/User/UserController';
import UserLayout from '@/layouts/UserLayout.vue';
import { home } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import { ShoppingCart, Package, ArrowRight } from 'lucide-vue-next';

interface Product {
    id: number;
    name: string;
    price: number;
    image: string | null;
    type: string;
    status: string;
}

interface Props {
    products: Product[];
    cartCount?: number;
}

withDefaults(defineProps<Props>(), {
    cartCount: 0,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Page',
        href: home(),
    },
];

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

const addToCart = (productId: number) => {
    router.post(
        `/cart/store/${productId}`,
        {},
        {
            preserveScroll: true,
        },
    );
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'MYR',
    }).format(price);
};
</script>

<template>
    <UserLayout :breadcrumbs="breadcrumbs" :cart-count="cartCount">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-foreground">
                            Our Products
                        </h2>
                        <p class="mt-2 text-muted-foreground">
                            Browse our latest collection
                        </p>
                    </div>
                </div>
                </div>

                <div v-if="products.length === 0" class="py-16 text-center">
                    <div
                        class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-muted"
                    >
                        <Package class="h-10 w-10 text-muted-foreground" />
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-foreground">
                        No products available
                    </h3>
                    <p class="mb-6 text-muted-foreground">
                        Check back later for new products.
                    </p>
                </div>

                <div
                    v-else
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <Card
                        v-for="product in products"
                        :key="product.id"
                        class="group overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div
                            class="relative aspect-square overflow-hidden bg-muted"
                        >
                            <img
                                v-if="product.image"
                                :src="`/storage/${product.image}`"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center"
                            >
                                <Package
                                    class="h-16 w-16 text-muted-foreground"
                                />
                            </div>
                            <div
                                v-if="product.status"
                                class="absolute top-3 right-3"
                            >
                                <Badge
                                    :variant="
                                        product.status === 'Available'
                                            ? 'default'
                                            : 'secondary'
                                    "
                                >
                                    {{ product.status }}
                                </Badge>
                            </div>
                        </div>
                        <CardHeader class="p-4 pb-0">
                            <div class="mb-2 flex items-center justify-between">
                                <Badge variant="outline">{{
                                    product.type
                                }}</Badge>
                            </div>
                            <CardTitle
                                class="line-clamp-2 text-lg font-semibold"
                                >{{ product.name }}</CardTitle
                            >
                        </CardHeader>
                        <CardContent class="p-4 pt-0">
                            <p class="text-2xl font-bold text-primary">
                                {{ formatPrice(product.price) }}
                            </p>
                        </CardContent>
                        <CardFooter class="p-4 pt-0">
                            <Button
                                @click="addToCart(product.id)"
                                class="w-full gap-2"
                            >
                                <ShoppingCart class="h-4 w-4" />
                                Add to Cart
                            </Button>
                        </CardFooter>
                    </Card>
                </div>

                <div
                    v-if="products.length > 0"
                    class="mt-8 flex justify-center"
                >
                    <Button variant="outline" as-child>
                        <Link :href="productView()" class="gap-2">
                            View All Products
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                    </Button>
                </div>
            </div>
    ></UserLayout>
</template>
