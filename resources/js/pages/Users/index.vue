<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface User {
    id: number;
    name: string;
    email: string;
}

defineProps<{
    users: User[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Link
                :href="route('users.create')"
                class="inline-flex max-w-fit items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            >
                Create User
            </Link>
            <div
                class="overflow-x-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <table
                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                >
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                ID
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Name
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Email
                            </th>
                            <th />
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900"
                    >
                        <tr v-for="user in users" :key="user.id">
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ user.id }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ user.name }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ user.email }}
                            </td>
                            <td
                                class="flex items-center justify-center gap-x-2 px-6 py-4"
                            >
                                <Link :href="route('users.show', user.id)">
                                    <Button
                                        class="cursor-pointer bg-slate-500 text-xs font-medium text-white hover:bg-slate-700"
                                        >View</Button
                                    >
                                </Link>
                                <Link :href="route('users.edit', user.id)">
                                    <Button
                                        class="cursor-pointer bg-blue-500 text-xs font-medium text-white hover:bg-blue-700"
                                        >Edit</Button
                                    >
                                </Link>
                                <Button
                                    class="cursor-pointer bg-red-500 text-xs font-medium text-white hover:bg-red-700"
                                    >Delete</Button
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
