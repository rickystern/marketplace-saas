<template>
  <div class="min-h-screen p-6">
    <h1 class="text-2xl font-bold">Products</h1>
    <a :href="`/marketplaces/${marketplace.slug}/products/create`" class="underline">Create product</a>
    <ul class="mt-4 space-y-2">
      <li v-for="product in products" :key="product.id">
        {{ product.name }} - {{ product.price }} ({{ product.status }})
        <a :href="`/marketplaces/${marketplace.slug}/products/${product.id}/edit`" class="underline ml-2">Edit</a>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
defineProps({ marketplace: Object, products: Array });
</script>