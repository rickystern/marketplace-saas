<template>
  <div class="min-h-screen px-4 py-8 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Review Applications</h1>
    <ul class="space-y-2">
      <li v-for="a in applications" :key="a.id" class="border p-3">
        {{ a.proposed_name }} ({{ a.proposed_slug }})
        <form method="post" :action="`/admin/marketplace-applications/${a.id}/approve`" class="inline">
          <button class="bg-green-600 text-white px-2 py-1 rounded ml-2">Approve</button>
        </form>
        <form method="post" :action="`/admin/marketplace-applications/${a.id}/reject`" class="inline">
          <button class="bg-red-600 text-white px-2 py-1 rounded ml-2">Reject</button>
        </form>
      </li>
    </ul>
  </div>
</template>
<script setup lang="ts">
defineProps<{ applications: any[] }>();
</script>
