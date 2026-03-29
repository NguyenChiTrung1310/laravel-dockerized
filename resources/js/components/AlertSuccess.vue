<script setup lang="ts">
import { AlertCircle } from 'lucide-vue-next';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

type Props = {
    title?: string;
    message?: string;
};

withDefaults(defineProps<Props>(), {
    title: 'Success',
    message: 'Your action was successful.',
});
</script>

<template>
    <Alert variant="success">
        <AlertCircle class="size-4" />
        <AlertTitle>{{ title }}</AlertTitle>
        <AlertDescription>{{ message }}</AlertDescription>
    </Alert>
</template>
