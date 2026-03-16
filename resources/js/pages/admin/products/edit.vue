<script setup lang="ts">
import { Form, Head, Link, useForm } from '@inertiajs/vue3';
import { onBeforeUnmount, ref } from 'vue';
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
import { edit, index } from '@/routes/admin/products';


interface Product {
    id: number;
    name: string;
    price: number;
    image: File | null;
    type: string;
    status: string;
}

const props = defineProps<{ product: Product }>();

// Create a ref for image preview
const imagePreview = ref<string | null>(null);

// Initialize preview with existing image if available
if (props.product.image) {
    imagePreview.value = `/storage/${props.product.image}`;
}

const form = useForm({
    name: props.product.name,
    price: props.product.price,
    image: null as File | null,
    type: props.product.type,
    status: props.product.status,
    _method: 'PUT',
});

const handleImageChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0] || null;

    if (file) {
        form.image = file;

        // Clean up previous preview
        if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
            URL.revokeObjectURL(imagePreview.value);
        }

        // Create new preview
        imagePreview.value = URL.createObjectURL(file);
    } else {
        form.image = null;
        // Revert to original image
        imagePreview.value = props.product.image ? `/storage/${props.product.image}` : null;
    }
}

const handleSubmit = () => {

    form.post(`/admin/products/${props.product.id}`, {

        forceFormData: true,
        preserveState: true,

        onSuccess: () => {
            // Clear preview if it's a blob URL
            if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
                URL.revokeObjectURL(imagePreview.value);
            }
        }
    });
}

// Clean up blob URLs when component is destroyed
onBeforeUnmount(() => {
    if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
    }
});

</script>

<template>

    <Head title="Edit Product" />

    <AppLayout :breadcrumbs="[{ title: 'Edit a Product', href: edit(product.id) }]">
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

                    <!-- Current image preview -->
                    <div v-if="imagePreview" class="mb-2">
                        <img :src="imagePreview" alt="Product Image Preview"
                            class="w-32 h-32 object-cover rounded border" />
                        <p class="text-sm text-gray-500 mt-1">
                            {{ form.image ? 'New image selected' : 'Current image' }}
                        </p>
                    </div>

                    <!-- File input -->
                    <Input type="file" accept="image/*" @change="handleImageChange" :disabled="form.processing" />

                    <!-- Help text -->
                    <p class="text-sm text-gray-500">
                        Leave empty to keep the current image
                    </p>

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
                    {{ form.processing ? 'Updating...' : 'Update Product' }}
                </Button>
                <Link :href=index()><Button>Back</Button></Link>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
