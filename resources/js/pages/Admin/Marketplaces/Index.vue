<template>
  <div class="min-h-screen px-4 py-8 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Marketplaces</h1>
    <ul class="space-y-2">
      <li v-for="m in marketplaces" :key="m.id" class="border p-3 flex items-center justify-between">
        <span>{{ m.name }} ({{ m.slug }}) - {{ m.status }}</span>
        <span>
          <form v-if="m.status === 'pending'" method="post" :action="`/admin/marketplaces/${m.id}/activate`" class="inline">
            <button class="bg-green-600 text-white px-2 py-1 rounded ml-2">Activate</button>
          </form>
          <form v-if="m.status === 'active'" method="post" :action="`/admin/marketplaces/${m.id}/suspend`" class="inline">
            <button class="bg-red-600 text-white px-2 py-1 rounded ml-2">Suspend</button>
          </form>
          <form v-if="m.status === 'suspended'" method="post" :action="`/admin/marketplaces/${m.id}/reactivate`" class="inline">
            <button class="bg-blue-600 text-white px-2 py-1 rounded ml-2">Reactivate</button>
          </form>
        </span>
      </li>
    </ul>
  </div>
</template>
<script setup lang="ts">
defineProps<{ marketplaces: any[] }>();
</script>