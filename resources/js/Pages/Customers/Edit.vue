<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { Customer, CustomerCategory } from '@/Models';

interface Props {
    customer: Customer;
    customerCategories: CustomerCategory[];
}

const props = defineProps<Props>();

// Reactive data
const showAddContactModal = ref(false);

const form = useForm({
    name: props.customer.name || '',
    reference: props.customer.reference || '',
    customer_category_id: props.customer.customer_category_id || '',
    started_at: props.customer.started_at || '',
    description: props.customer.description || '',
});

const handleSubmit = () => {
    form.put(`/customers/${props.customer.id}`, {
        onSuccess: () => {
            // Handle success
        },
    });
};

const handleBack = () => {
    router.visit('/customers');
};

const handleDelete = () => {
    if (confirm('Are you sure you want to delete this customer?')) {
        router.delete(`/customers/${props.customer.id}`);
    }
};
</script>

<template>
    <Head :title="`Edit Customer - ${customer.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Customer - {{ props.customer.name }}
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
                        {{ form.processing ? 'Saving...' : 'Save' }}
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- General Section -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    General
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Name
                                    </label>
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.name }"
                                    />
                                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                                </div>

                                <!-- Reference -->
                                <div>
                                    <label for="reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Reference
                                    </label>
                                    <input
                                        id="reference"
                                        v-model="form.reference"
                                        type="text"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.reference }"
                                    />
                                    <p v-if="form.errors.reference" class="mt-1 text-sm text-red-600">{{ form.errors.reference }}</p>
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Category
                                    </label>
                                    <select
                                        id="category"
                                        v-model="form.customer_category_id"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.customer_category_id }"
                                    >
                                        <option value="">-- Select --</option>
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
                                        Start Date
                                    </label>
                                    <input
                                        id="started_at"
                                        v-model="form.started_at"
                                        type="date"
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
                                    ></textarea>
                                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contacts Section -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Contacts
                                </h3>
                                <button
                                    type="button"
                                    @click="showAddContactModal = true"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Contact
                                </button>
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <div v-if="customer.contacts && customer.contacts.length > 0" class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                First Name
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Last Name
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <tr v-for="contact in customer.contacts" :key="contact.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ contact.first_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ contact.last_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <button type="button" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                                    Edit
                                                </button>
                                                <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No contacts assigned to this customer.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border-l-4 border-red-500">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-red-600 dark:text-red-400">
                                        Danger Zone
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Once you delete a customer, there is no going back. Please be certain.
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    @click="handleDelete"
                                    class="inline-flex items-center px-4 py-2 border border-red-300 dark:border-red-600 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                >
                                    Delete Customer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Contact Modal -->
        <div v-if="showAddContactModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showAddContactModal = false"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Add Contact
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            First Name
                                        </label>
                                        <input
                                            type="text"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Last Name
                                        </label>
                                        <input
                                            type="text"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Email
                                        </label>
                                        <input
                                            type="email"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="showAddContactModal = false"
                        >
                            Add Contact
                        </button>
                        <button
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="showAddContactModal = false"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
