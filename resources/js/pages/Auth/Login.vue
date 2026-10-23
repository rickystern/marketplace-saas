<template>
  <div class="min-h-screen flex items-center justify-center px-4">
    <form class="w-full max-w-md space-y-4" @submit.prevent="submit">
      <h1 class="text-2xl font-bold">Login</h1>
      <div>
        <label class="block text-sm">Email</label>
        <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
        <p v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</p>
      </div>
      <div>
        <label class="block text-sm">Password</label>
        <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2" />
      </div>
      <button class="w-full bg-blue-600 text-white py-2 rounded" :disabled="form.processing">Log in</button>
      <p class="text-sm"><a href="/register" class="text-blue-600">Register</a> · <a href="/forgot-password" class="text-blue-600">Forgot password</a></p>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({ email: '', password: '', remember: false });

function submit() {
  form.post('/login');
}
</script>
