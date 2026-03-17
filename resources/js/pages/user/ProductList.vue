<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed, watch, ref } from 'vue';
import { toast } from 'vue-sonner';
import UserLayout from '@/layouts/UserLayout.vue';
import type { BreadcrumbItem } from '@/types';

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
        href: '',
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

const getTypeColor = (type: string) => {
    const colors: Record<string, string> = {
        laptop: 'bg-blue-100 text-blue-800',
        phone: 'bg-green-100 text-green-800',
        watch: 'bg-purple-100 text-purple-800',
        default: 'bg-gray-100 text-gray-800',
    };

    return colors[type.toLowerCase()] || colors.default;
};
</script>

<template>
    <UserLayout :breadcrumbs="breadcrumbs" :cart-count="cartCount">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900">
                        Products
                    </h2>
                    <p class="mt-2 text-gray-600">
                        Browse our complete collection
                    </p>
                </div>

                <div
                    class="mb-6 rounded-lg border border-gray-200 bg-white p-4 shadow-sm"
                >
                    <div
                        class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center"
                        >
                            <div class="w-full sm:w-48">
                                <label
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Search</label
                                >
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search products..."
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                            <div class="w-full sm:w-40">
                                <label
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Type</label
                                >
                                <select
                                    v-model="selectedType"
                                    placeholder="Type"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                                >
                                    <option
                                        v-for="type in productTypes"
                                        :key="type.value"
                                        :value="type.value"
                                    >
                                        {{ type.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="w-full sm:w-44">
                                <label
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Sort By</label
                                >
                                <select
                                    v-model="selectedSort"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                                >
                                    <option default value="all">
                                        All Types
                                    </option>
                                    <option
                                        v-for="option in sortOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button
                                @click="applyFilters"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-indigo-500"
                            >
                                Apply Filters
                            </button>
                            <button
                                @click="clearFilters"
                                class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-300"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="products.length === 0" class="py-16 text-center">
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
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                        />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">
                        No products found
                    </h3>
                    <p class="mt-2 text-gray-500">
                        Try adjusting your filters or search criteria.
                    </p>
                </div>

                <div v-else class="mb-4 text-sm text-gray-600">
                    Showing {{ products.length }} product(s)
                </div>

                <div
                    v-if="products.length > 0"
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div
                            class="aspect-square w-full overflow-hidden bg-gray-200"
                        >
                            <img
                                v-if="product.image"
                                :src="`/storage/${product.image}`"
                                :alt="product.name"
                                class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-gray-100"
                            >
                                <svg
                                    class="h-16 w-16 text-gray-300"
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
                            <div
                                v-if="product.status"
                                class="absolute top-3 right-3"
                            >
                                <span
                                    :class="
                                        product.status === 'Available'
                                            ? 'bg-green-500'
                                            : 'bg-gray-500'
                                    "
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white"
                                >
                                    {{ product.status }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="mb-2">
                                <span
                                    :class="getTypeColor(product.type)"
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                >
                                    {{ product.type }}
                                </span>
                            </div>
                            <h3
                                class="text-lg font-semibold text-gray-900 transition-colors group-hover:text-indigo-600"
                            >
                                {{ product.name }}
                            </h3>
                            <p class="mt-2 text-xl font-bold text-indigo-600">
                                {{ formatPrice(product.price) }}
                            </p>
                            <button
                                @click="addToCart(product.id)"
                                class="mt-4 w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
