<script setup>
const props = defineProps({
  tickets: Array
});
</script>

<template>
  <table>
    <thead>
    <tr>
      <th>Movie</th>
      <th>Price</th>
      <th>Total</th>
      <th>Available</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="ticket in tickets" :key="ticket.id">
      <th class="text-left">{{ ticket.movie_title }}</th>
      <td class="text-center">{{ ticket.price }}</td>
      <td class="text-center">{{ ticket.total }}</td>
      <td class="text-center">{{ ticket.available }}</td>
    </tr>
    </tbody>
  </table>
</template>
