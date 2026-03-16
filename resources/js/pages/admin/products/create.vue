<script setup lang="ts">
import { Form, Head, Link, useForm } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AdminLayout.vue';
import { create, index } from '@/routes/admin/products';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a Product',
        href: create(),
    },
];

const form = useForm({
    name: '',
    price: '',
    image: null as File | null,
    type: '',
    status: '',
});

const handleSubmit = () => {
    form.post('/admin/products/store');
};
</script>

<template>

    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Products</h1>
            </div>
            <Form @submit.prevent="handleSubmit">
                <div class="mb-5 space-y-4">
                    <Label for="Product Name">Name</Label>
                    <Input v-model="form.name" type="text" placeholder="Enter Product Name" />
                    <div class="text-lg text-red-500" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </div>
                <div class="mb-5 space-y-4">
                    <Label for="Product Price">Price</Label>
                    <Input v-model="form.price" type="number" step="0.01" min="0" placeholder="Enter Product Price" />
                    <div class="text-lg text-red-500" v-if="form.errors.price">
                        {{ form.errors.price }}
                    </div>
                </div>
                <div class="mb-5 space-y-4">
                    <Label for="Product Image">Image</Label>
                    <Input type="file" accept="image/*" @change="
                        (e) =>
                        (form.image =
                            (e.target as HTMLInputElement).files?.[0] ||
                            null)
                    " />
                    <div class="text-lg text-red-500" v-if="form.errors.image">
                        {{ form.errors.image }}
                    </div>
                </div>
                <div class="mb-5 space-y-4">
                    <Label for="Product Type">Type</Label>
                    <Select v-model="form.type">
                        <SelectTrigger>
                            <SelectValue placeholder="Product Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem value="laptop"> Laptop </SelectItem>
                                <SelectItem value="phone"> Phone </SelectItem>
                                <SelectItem value="watch"> Watch </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <div class="text-lg text-red-500" v-if="form.errors.type">
                        {{ form.errors.type }}
                    </div>
                </div>
                <div class="mb-5 space-y-4">
                    <Label for="Product Status">Status</Label>
                    <Select v-model="form.status">
                        <SelectTrigger>
                            <SelectValue placeholder="Product Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem value="Available">
                                    Available
                                </SelectItem>
                                <SelectItem value="Pending">
                                    Pending
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <div class="text-lg text-red-500" v-if="form.errors.status">
                        {{ form.errors.status }}
                    </div>
                </div>
                <div class="space-x-10">
                <Button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Creating...' : 'Create a Product' }}
                </Button>
                <Link :href=index()><Button>Back</Button></Link>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
