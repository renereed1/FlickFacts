<script setup>
const props = defineProps({
  sales: Array
});
</script>

<template>
  <table>
    <thead>
    <tr>
      <th>Movie</th>
      <th>Price</th>
      <th>Sold</th>
      <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="sale in sales" :key="sale.id">
      <th class="text-left">{{ sale.movie }}</th>
      <td class="text-center">{{ sale.price }}</td>
      <td class="text-center">{{ parseInt(sale.tickets_sold) }}</td>
      <td class="text-center">${{ parseFloat(sale.total_revenue).toFixed(2) }}</td>
    </tr>
    </tbody>
  </table>
</template>