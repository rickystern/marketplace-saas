<template>
  <div class="min-h-screen p-6">
    <h1 class="text-2xl font-bold">Edit product</h1>
    <form method="post" :action="`/marketplaces/${marketplace.slug}/products/${product.id}`">
      <input type="hidden" name="_method" value="PATCH" />
      <input name="name" :value="product.name" class="border p-2 block" />
      <textarea name="description" class="border p-2 block mt-2">{{ product.description }}</textarea>
      <input name="price" :value="product.price" class="border p-2 block mt-2" />
      <button class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Update</button>
    </form>
  </div>
</template>

<script setup lang="ts">
defineProps({ marketplace: Object, product: Object });
</script>