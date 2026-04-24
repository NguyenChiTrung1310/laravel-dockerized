<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue'
import type { BreadcrumbItem } from '@/types';

interface Role {
  id: number;
  name: string;
}


const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Roles',
    href: '/roles',
  },
];

defineProps<{
  roles: Role[];
}>();

const deleteRole = (id: number) => {
  if (confirm('Are you sure you want to delete this role?')) {
    
  }
}

</script>

<template>

  <Head title="Roles" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <Link :href="route('roles.create')"
        class="inline-flex max-w-fit items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50">
      Create Role
      </Link>
      <div class="overflow-x-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                ID
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                Name
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
            <tr v-for="role in roles" :key="role.id">
              <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100">
                {{ role.id }}
              </td>
              <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100">
                {{ role.name }}
              </td>
              <td class="flex items-center justify-center gap-x-2 px-6 py-4">
                <Link :href="route('roles.show', role.id)">
                <Button
                  class="cursor-pointer bg-slate-500 text-xs font-medium text-white hover:bg-slate-700">View</Button>
                </Link>
                <Link :href="route('roles.edit', role.id)">
                <Button
                  class="cursor-pointer bg-blue-500 text-xs font-medium text-white hover:bg-blue-700">Edit</Button>
                </Link>
                <Button @click="deleteRole(role.id)"
                  class="cursor-pointer bg-red-500 text-xs font-medium text-white hover:bg-red-700">Delete</Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>