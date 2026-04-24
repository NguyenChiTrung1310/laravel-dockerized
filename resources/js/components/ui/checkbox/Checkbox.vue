<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { Check } from 'lucide-vue-next'
import { CheckboxIndicator, CheckboxRoot } from 'reka-ui'
import { cn } from '@/lib/utils'

const props = defineProps<{
    class?: HTMLAttributes['class']
    label?: string
    name?: string
    value?: string
    description?: string
    error?: string
    disabled?: boolean
}>()

const modelValue = defineModel<boolean | 'indeterminate'>({ default: false })
</script>

<template>
  <div class="flex flex-col gap-1">
    <label class="flex cursor-pointer items-center gap-2">
      <CheckboxRoot
        v-slot="slotProps"
        v-model="modelValue"
        :name="name"
        :value="value"
        :disabled="disabled"
        data-slot="checkbox"
        :aria-invalid="!!error"
        :class="
          cn(
            'peer border-input data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground data-[state=checked]:border-primary focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive size-4 shrink-0 rounded-sm border shadow-xs transition-shadow outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50',
            props.class,
          )
        "
      >
        <CheckboxIndicator
          data-slot="checkbox-indicator"
          class="grid place-content-center text-current transition-none"
        >
          <slot v-bind="slotProps">
            <Check class="size-3.5" />
          </slot>
        </CheckboxIndicator>
      </CheckboxRoot>

      <div v-if="label || description" class="flex flex-col gap-0.5">
        <span
          v-if="label"
          class="text-sm capitalize leading-none font-medium select-none peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
        >
          {{ label }}
        </span>
        <p v-if="description" class="text-muted-foreground text-xs">
          {{ description }}
        </p>
      </div>
    </label>

    <p v-if="error" class="text-destructive text-sm">
      {{ error }}
    </p>
  </div>
</template>
