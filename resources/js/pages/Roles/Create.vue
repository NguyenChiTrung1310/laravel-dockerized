<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import Button from '@/components/ui/button/Button.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Role Create',
        href: '/roles',
    },
];

defineProps<{
    permissions: string[];
}>();

const form = useForm<{
    name: string;
    permission: string[];
}>({
    name: '',
    permission: [],
});

function togglePermission(permission: string, checked: boolean | 'indeterminate') {
    if (checked) {
        if (!form.permission.includes(permission)) {
            form.permission.push(permission);
        }
    } else {
        const idx = form.permission.indexOf(permission);

        if (idx !== -1) {
            form.permission.splice(idx, 1);
        }
    }
}

function submit() {
    form.post(route('roles.store'), {
        onSuccess: (page) => {
            const flash = (page.props as any).flash;

            if (flash?.success) {
                toast.success(flash.success);
            }

            form.reset();
        },
    });
}
</script>

<template>
    <Head title="Roles Create" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="overflow-x-auto p-3">
            <Link
                :href="route('roles.index')"
                class="inline-flex max-w-fit items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            >
                Back
            </Link>

            <form class="mx-auto mt-4 max-w-md space-y-6" @submit.prevent="submit">
                <Input
                    label="Name"
                    name="name"
                    type="text"
                    placeholder="Enter name"
                    v-model="form.name"
                    required
                    :error="form.errors.name"
                />

                <div class="space-y-2">
                    <Label>Permissions</Label>
                    <Checkbox
                        v-for="permission in permissions"
                        :key="permission"
                        :label="permission"
                        :value="permission"
                        :model-value="form.permission.includes(permission)"
                        @update:model-value="(checked) => togglePermission(permission, checked)"
                    />
                    <p v-if="form.errors.permission" class="text-sm text-destructive">
                        {{ form.errors.permission }}
                    </p>
                </div>

                <Button type="submit" class="w-full">Submit</Button>
            </form>
        </div>
    </AppLayout>
</template>
