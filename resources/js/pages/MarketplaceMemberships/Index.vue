<template>
  <div>
    <h1>Memberships</h1>
    <ul>
      <li v-for="m in memberships" :key="m.id">
        {{ m.user?.name }} - {{ m.role }} - {{ m.status }}
        <button @click="toggle(m)">{{ m.status === 'active' ? 'Deactivate' : 'Reactivate' }}</button>
      </li>
    </ul>
    <form @submit.prevent="add">
      <input v-model="form.user_id" type="number" placeholder="User ID" />
      <select v-model="form.role">
        <option value="seller">seller</option>
        <option value="delivery_bearer">delivery_bearer</option>
      </select>
      <button type="submit">Add</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({ marketplace: Object, memberships: Array });

const form = ref({ user_id: '', role: 'seller' });

function add() {
  router.post(`/marketplaces/${props.marketplace.slug}/memberships`, form.value);
}

function toggle(m) {
  router.patch(`/marketplaces/${props.marketplace.slug}/memberships/${m.id}`, {
    status: m.status === 'active' ? 'inactive' : 'active',
  });
}
</script>