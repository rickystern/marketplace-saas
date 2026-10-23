<template>
  <div class="min-h-screen px-4 py-8 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">My Applications</h1>
    <ul>
      <li v-for="a in applications" :key="a.id">{{ a.proposed_name }} - {{ a.status }}</li>
    </ul>
    <a href="/marketplace-applications/create" class="text-blue-600">New</a>
  </div>
</template>
<script setup lang="ts">
defineProps<{ applications: any[] }>();
</script>
