<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataList from '@/Components/DataList.vue';
import CustomerTableRow from '@/Components/Customers/CustomerTableRow.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import type { Customer } from '@models/Customer';
import type { TableColumn } from '@/types/table';

interface Props {
    customers: Customer[];
}

const props = defineProps<Props>();

const searchQuery = ref('');

// Define the columns configuration
const columns: TableColumn[] = [
    { key: 'customer', label: 'Customer', visible: true, width: '300px' },
    { key: 'category', label: 'Category', visible: true },
    { key: 'started', label: 'Started', visible: true },
    { key: 'actions', label: '', visible: true, align: 'right', width: '50px' },
];

const filteredCustomers = computed(() => {
    if (!searchQuery.value) return props.customers;

    const query = searchQuery.value.toLowerCase();
    return props.customers.filter(customer =>
        customer.name.toLowerCase().includes(query) ||
        customer.reference?.toLowerCase().includes(query) ||
        customer.description?.toLowerCase().includes(query) ||
        customer.customer_category?.name.toLowerCase().includes(query)
    );
});

const handleSearch = (query: string) => {
    searchQuery.value = query;
};

const handleCreate = () => {
    router.visit('/customers/create');
};

const handleItemClick = (customer: Customer) => {
    router.visit(`/customers/${customer.id}`);
};
</script>

<template>
    <Head title="Customers" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Customers
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <DataList
                    title="All Customers"
                    :subtitle="`${filteredCustomers.length} customer${filteredCustomers.length !== 1 ? 's' : ''} found`"
                    :items="filteredCustomers"
                    :columns="columns"
                    search-placeholder="Search customers by name, reference, or category..."
                    create-button-text="Add Customer"
                    empty-state-title="No customers found"
                    empty-state-message="Get started by adding your first customer."
                    @search="handleSearch"
                    @create="handleCreate"
                    @item-click="handleItemClick"
                >
                    <template #items="{ items, columns }">
                        <CustomerTableRow
                            v-for="customer in items"
                            :key="customer.id"
                            :item="customer"
                            :columns="columns"
                            @click="handleItemClick(customer)"
                        />
                    </template>
                </DataList>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
