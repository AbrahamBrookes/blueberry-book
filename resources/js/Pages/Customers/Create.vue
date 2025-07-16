<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import type { CustomerCategory } from '@models/CustomerCategory';

interface Props {
    customerCategories: CustomerCategory[];
}

const props = defineProps<Props>();

const form = useForm({
    name: '',
    reference: '',
    customer_category_id: '',
    started_at: '',
    description: '',
});

const handleSubmit = () => {
    form.post('/customers', {
        onSuccess: () => {
            // Redirect handled by controller
        },
    });
};

const handleBack = () => {
    router.visit('/customers');
};
</script>

<template>
    <Head title="Create Customer" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Create Customer
                </h2>
                <div class="flex space-x-3">
                    <button
                        @click="handleBack"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Back
                    </button>
                    <button
                        @click="handleSubmit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Creating...' : 'Create Customer' }}
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- General Section -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    General Information
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Name <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.name }"
                                        placeholder="Enter customer name"
                                    />
                                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                                </div>

                                <!-- Reference -->
                                <div>
                                    <label for="reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Reference <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="reference"
                                        v-model="form.reference"
                                        type="text"
                                        required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.reference }"
                                        placeholder="Enter customer reference"
                                    />
                                    <p v-if="form.errors.reference" class="mt-1 text-sm text-red-600">{{ form.errors.reference }}</p>
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="category"
                                        v-model="form.customer_category_id"
                                        required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.customer_category_id }"
                                    >
                                        <option value="">-- Select Category --</option>
                                        <option
                                            v-for="category in customerCategories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.customer_category_id" class="mt-1 text-sm text-red-600">{{ form.errors.customer_category_id }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Details
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <!-- Start Date -->
                                <div>
                                    <label for="started_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Start Date <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="started_at"
                                        v-model="form.started_at"
                                        type="date"
                                        required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.started_at }"
                                    />
                                    <p v-if="form.errors.started_at" class="mt-1 text-sm text-red-600">{{ form.errors.started_at }}</p>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Description
                                    </label>
                                    <textarea
                                        id="description"
                                        v-model="form.description"
                                        rows="4"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.description }"
                                        placeholder="Enter customer description (optional)"
                                    ></textarea>
                                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Text -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <strong>Required fields are marked with *</strong><br>
                                    You can add contacts to this customer after creating it.
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
