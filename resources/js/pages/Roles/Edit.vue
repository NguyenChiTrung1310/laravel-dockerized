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
        title: 'Roles',
        href: '/roles',
    },
    {
        title: 'Edit',
        href: '',
    },
];

const props = defineProps<{
    role: {name: string; permissions: string[]; id: number} | null,
    permissions: string[];
}>();


const form = useForm<{
    name: string;
    permissions: string[];
}>({
    name: props.role?.name || '',
    permissions: props.role?.permissions || [],
});

function togglePermission(permission: string, checked: boolean | 'indeterminate') {
    if (checked) {
        if (!form.permissions.includes(permission)) {
            form.permissions.push(permission);
        }
    } else {
        const idx = form.permissions.indexOf(permission);

        if (idx !== -1) {
            form.permissions.splice(idx, 1);
        }
    }
}

function submit() {
    form.put(route('roles.update', props.role?.id), {
        onSuccess: (page) => {
            console.log('🚀 ~ submit ~ page:', page);
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
    <Head title="Edit Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="overflow-x-auto p-3">
            <Link
                :href="route('roles.index')"
                class="inline-flex max-w-fit items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            >
                Back
            </Link>

            <form
                class="mx-auto mt-4 max-w-md space-y-6"
                @submit.prevent="submit"
            >
                <Input label="Name" name="name" type="text" placeholder="Enter name" v-model="form.name" required
                    :error="form.errors.name" />

                <div class="space-y-2">
                    <Label>Permissions</Label>
                    <Checkbox v-for="permission in permissions" :key="permission" :label="permission"
                        :value="permission" :model-value="form.permissions.includes(permission)"
                        @update:model-value="(checked) => togglePermission(permission, checked)" />
                    <p v-if="form.errors.permissions" class="text-sm text-destructive">
                        {{ form.errors.permissions }}
                    </p>
                </div>

                <Button type="submit" class="w-full"> Submit </Button>
            </form>
        </div>
    </AppLayout>
</template>

<style lang=""></style>
