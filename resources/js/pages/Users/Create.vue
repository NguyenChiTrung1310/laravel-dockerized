<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Create',
        href: '/users',
    },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
});

function submit() {
    form.post(route('users.store'), {
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            toast.error('Please fix the errors below.');
        },
    });
}
</script>

<template>
    <Head title="Users Create" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="overflow-x-auto p-3">
            <Link
                :href="route('users.index')"
                class="inline-flex max-w-fit items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            >
                Back
            </Link>

            <form
                class="mx-auto mt-4 max-w-md space-y-6"
                @submit.prevent="submit"
            >
                <Input
                    label="Name"
                    name="name"
                    type="text"
                    placeholder="Enter name"
                    v-model="form.name"
                    required
                    :error="form.errors.name"
                />
                <Input
                    label="Email"
                    name="email"
                    type="email"
                    v-model="form.email"
                    placeholder="Enter email"
                    required
                    :error="form.errors.email"
                />

                <Input
                    label="Password"
                    name="password"
                    type="password"
                    placeholder="Enter password"
                    required
                    v-model="form.password"
                    :error="form.errors.password"
                />

                <Button type="submit" class="w-full"> Submit </Button>
            </form>
        </div>
    </AppLayout>
</template>
