<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed, watch, ref } from 'vue';
import { toast } from 'vue-sonner';
import UserLayout from '@/layouts/UserLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { home } from '@/routes';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Search, X, ShoppingCart, Package, Filter } from 'lucide-vue-next';

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
    filters?: {
        type: string;
        sort: string;
        search: string;
    };
}

withDefaults(defineProps<Props>(), {
    cartCount: 0,
    filters: () => ({
        type: '',
        sort: 'name_asc',
        search: '',
    }),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Dashboard',
        href: home(),
    },
    {
        title: 'Products',
        href: '/products',
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

const selectedType = ref(page.props.filters?.type || 'all');
const selectedSort = ref(page.props.filters?.sort || 'name_asc');
const searchQuery = ref(page.props.filters?.search || '');

const productTypes = [
    { value: 'all', label: 'All Types' },
    { value: 'laptop', label: 'Laptop' },
    { value: 'phone', label: 'Phone' },
    { value: 'watch', label: 'Watch' },
];

const sortOptions = [
    { value: 'name_asc', label: 'Name: A-Z' },
    { value: 'name_desc', label: 'Name: Z-A' },
    { value: 'price_asc', label: 'Price: Low to High' },
    { value: 'price_desc', label: 'Price: High to Low' },
];

const applyFilters = () => {
    router.get(
        '/products',
        {
            type: selectedType.value,
            sort: selectedSort.value,
            search: searchQuery.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

const clearFilters = () => {
    selectedType.value = 'all';
    selectedSort.value = 'name_asc';
    searchQuery.value = '';
    router.get(
        '/products',
        {},
        {
            preserveState: true,
            replace: true,
        },
    );
};

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
                <div class="mb-8">
                    <h2
                        class="text-3xl font-bold tracking-tight text-foreground"
                    >
                        Products
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        Browse our complete collection
                    </p>
                </div>

                <Card class="mb-6">
                    <CardHeader class="pb-4">
                        <div class="flex items-center gap-2">
                            <Filter class="h-5 w-5 text-muted-foreground" />
                            <CardTitle class="text-lg">Filters</CardTitle>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
                        >
                            <div class="space-y-2">
                                <label class="text-sm font-medium"
                                    >Search</label
                                >
                                <div class="relative">
                                    <Search
                                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                                    />
                                    <Input
                                        v-model="searchQuery"
                                        type="text"
                                        placeholder="Search products..."
                                        class="pl-9"
                                        @keyup.enter="applyFilters"
                                    />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Type</label>
                                <Select v-model="selectedType">
                                    <SelectTrigger>
                                        <SelectValue
                                            placeholder="Select type"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="type in productTypes"
                                            :key="type.value"
                                            :value="type.value"
                                        >
                                            {{ type.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium"
                                    >Sort By</label
                                >
                                <Select v-model="selectedSort">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Sort by" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="option in sortOptions"
                                            :key="option.value"
                                            :value="option.value"
                                        >
                                            {{ option.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="flex items-end gap-2">
                                <Button
                                    @click="applyFilters"
                                    class="flex-1 gap-2"
                                >
                                    Apply
                                </Button>
                                <Button
                                    @click="clearFilters"
                                    variant="outline"
                                    size="icon"
                                >
                                    <X class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div v-if="products.length === 0" class="py-16 text-center">
                    <div
                        class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-muted"
                    >
                        <Package class="h-10 w-10 text-muted-foreground" />
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-foreground">
                        No products found
                    </h3>
                    <p class="mb-6 text-muted-foreground">
                        Try adjusting your filters or search criteria.
                    </p>
                    <Button variant="outline" @click="clearFilters">
                        Clear Filters
                    </Button>
                </div>

                <div
                    v-if="products.length > 0"
                    class="mb-4 text-sm text-muted-foreground"
                >
                    Showing {{ products.length }} product(s)
                </div>

                <div
                    v-if="products.length > 0"
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
            </div>
        </div>
    </UserLayout>
</template>
