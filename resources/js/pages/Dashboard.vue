<template>
  <div class="min-h-screen px-4 py-8">
    <div class="max-w-3xl mx-auto space-y-4">
      <h1 class="text-2xl font-bold">Dashboard</h1>
      <p>Welcome, {{ $page.props.auth.user?.name }}</p>

      <div class="space-y-2">
        <a href="/marketplace-applications" class="block underline">Marketplace applications</a>
        <a href="/marketplace-applications/create" class="block underline">Apply for a marketplace</a>
      </div>

      <div v-if="isPlatformAdmin" class="space-y-2">
        <a href="/admin/marketplace-applications" class="block underline">Review marketplace applications</a>
        <a href="/admin/marketplaces" class="block underline">Manage marketplaces</a>
      </div>

      <div>
        <h2 class="font-semibold">Your marketplaces</h2>
        <ul class="space-y-1">
          <li v-for="membership in marketplaces" :key="membership.id">
            <a :href="`/marketplaces/${membership.marketplace.slug}`" class="underline">
              {{ membership.marketplace.name }}
            </a>
            <span v-if="membership.role === 'owner'">
              - <a :href="`/marketplaces/${membership.marketplace.slug}/memberships`" class="underline">Manage members</a>
            </span>
          </li>
        </ul>
      </div>

      <form method="post" action="/logout">
        <button class="bg-red-600 text-white px-4 py-2 rounded">Log out</button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const $page = usePage();
const props = defineProps({
  marketplaces: Array,
  isPlatformAdmin: Boolean,
});

const marketplaces = props.marketplaces ?? [];
const isPlatformAdmin = props.isPlatformAdmin;
</script>
