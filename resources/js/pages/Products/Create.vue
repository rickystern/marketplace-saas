<template>
  <div class="min-h-screen p-6">
    <h1 class="text-2xl font-bold">Create product</h1>
    <form method="post" :action="`/marketplaces/${marketplace.slug}/products`">
      <input name="name" placeholder="Name" class="border p-2 block" />
      <textarea name="description" placeholder="Description" class="border p-2 block mt-2"></textarea>
      <input name="price" placeholder="Price" class="border p-2 block mt-2" />
      <button class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Create</button>
    </form>
  </div>
</template>

<script setup lang="ts">
defineProps({ marketplace: Object });
</script>