<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { toast } from 'vue-sonner';
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
import { create, edit, index } from '@/routes/admin/products';
import type { BreadcrumbItem } from '@/types';


interface Product {
    id: number,
    name: string,
    price: number,
    type: string,
    status: string,
}

interface Props{
    products: Product[];
}

// Geet props from inertia
const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: index(),
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
            });
        }
    },
    { immediate: true },
);


const handleDelete = (id: number) => {
    if (confirm('Do you want to delete this product?')){
        router.delete(`/admin/products/${id}`);
    }
}
</script>

<template>

    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Products</h1>
                <Link :href="create()">
                    <Button> + Create Product </Button>
                </Link>
            </div>
            <div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">
                                Name
                            </TableHead>
                            <TableHead>Price</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="product in props.products" :key="product.id">
                            <TableCell class="font-medium">
                                {{product.name}}
                            </TableCell>
                            <TableCell>{{product.price}}</TableCell>
                            <TableCell>{{product.type}}</TableCell>
                            <TableCell>
                                {{product.status}}
                            </TableCell>
                            <TableCell class="text-center space-x-4">
                                <Link :href=edit(product.id)><Button class="bg-blue-400">Edit</Button></Link>
                                <Button @click="handleDelete(product.id)" class="bg-red-400">Delete</Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
