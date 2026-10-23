<template>
  <div class="min-h-screen px-4 py-8 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Apply for Marketplace</h1>
    <form @submit.prevent="submit" class="space-y-4">
      <input v-model="form.proposed_name" placeholder="Name" class="border p-2 w-full" />
      <input v-model="form.proposed_slug" placeholder="slug" class="border p-2 w-full" />
      <button class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
    </form>
  </div>
</template>
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
const form = useForm({ proposed_name: '', proposed_slug: '' });
function submit() { form.post('/marketplace-applications'); }
</script>
