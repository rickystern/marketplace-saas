<template>
  <div class="min-h-screen flex items-center justify-center px-4">
    <form class="w-full max-w-md space-y-4" @submit.prevent="submit">
      <h1 class="text-2xl font-bold">Forgot Password</h1>
      <div v-if="$page.props.flash?.status" class="text-green-600">{{ $page.props.flash.status }}</div>
      <div>
        <label class="block text-sm">Email</label>
        <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
        <p v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</p>
      </div>
      <button class="w-full bg-blue-600 text-white py-2 rounded" :disabled="form.processing">Send reset link</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const form = useForm({ email: '' });

function submit() {
  form.post('/forgot-password');
}
</script>
