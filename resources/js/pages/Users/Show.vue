<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
    {
        title: 'User Details',
        href: '',
    },
];

defineProps({
    user: Object,
});
</script>

<template>
    <Head title="User Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="overflow-x-auto p-3">
            <Link
                :href="route('users.index')"
                class="inline-flex max-w-fit items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            >
                Back
            </Link>

            <div class="mx-auto mt-4 max-w-md space-y-6">
                <p><strong>Name:</strong> {{ user?.name }}</p>
                <p><strong>Email:</strong> {{ user?.email }}</p>
            </div>
        </div>
    </AppLayout>
</template>

<style lang=""></style>
