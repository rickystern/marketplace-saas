<template>
  <div class="min-h-screen flex items-center justify-center px-4">
    <form class="w-full max-w-md space-y-4" @submit.prevent="submit">
      <h1 class="text-2xl font-bold">Reset Password</h1>
      <input type="hidden" v-model="form.token" />
      <div>
        <label class="block text-sm">Email</label>
        <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
        <p v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</p>
      </div>
      <div>
        <label class="block text-sm">Password</label>
        <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2" />
        <p v-if="form.errors.password" class="text-red-600 text-sm">{{ form.errors.password }}</p>
      </div>
      <div>
        <label class="block text-sm">Confirm Password</label>
        <input v-model="form.password_confirmation" type="password" class="w-full border rounded px-3 py-2" />
      </div>
      <button class="w-full bg-blue-600 text-white py-2 rounded" :disabled="form.processing">Reset password</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{ token: string; email: string }>();

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

function submit() {
  form.post('/reset-password');
}
</script>
