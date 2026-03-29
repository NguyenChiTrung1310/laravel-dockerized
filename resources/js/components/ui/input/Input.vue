<script setup lang="ts">
import { computed, ref, type HTMLAttributes } from "vue"
import { useVModel } from "@vueuse/core"
import { Eye, EyeOff } from 'lucide-vue-next';
import { cn } from "@/lib/utils"

const props = defineProps<{
  defaultValue?: string | number
  modelValue?: string | number
  class?: HTMLAttributes["class"]
  label?: string
  type?: string
  required?: boolean
  error?: string
}>()

const emits = defineEmits<{
  (e: "update:modelValue", payload: string | number): void
}>()

const modelValue = useVModel(props, "modelValue", emits, {
  passive: true,
  defaultValue: props.defaultValue,
})

const showPassword = ref(false)
const inputType = computed(() => {
  if (props.type === "password") {
    return showPassword.value ? "text" : "password"
  }
  return props.type || "text"
})
</script>

<template>
  <label v-if="label" :for="label" :class="cn(
    'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70',
  )">
    {{ label }}
  </label>
  <div class="relative">
    <input
      v-bind="$attrs"
      v-model="modelValue"
      :type="inputType"
      :aria-invalid="!!error"
      data-slot="input"
      :class="cn(
        'peer file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
        'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
        'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
        props.class,
      )"
    >
    <p class="mt-1 max-h-0 overflow-hidden text-sm text-destructive opacity-0 transition-all duration-300 ease-in-out peer-aria-invalid:max-h-8 peer-aria-invalid:opacity-100">
      {{ error }}
    </p>
    <button
      v-if="type === 'password'"
      type="button"
      class="absolute right-2 top-4.5 -translate-y-1/2 text-sm text-muted-foreground"
      @click="showPassword = !showPassword"
    >
      <Eye v-if="showPassword" class="h-4 w-4 text-muted-foreground" />
      <EyeOff v-else class="h-4 w-4 text-muted-foreground" />
    </button>
  </div>
</template>
