<script setup>

const props = defineProps({
  sales: Array,
});

</script>

<template>
  <table>
    <thead>
    <tr>
      <th>Theater</th>
      <th class="px-5">Price</th>
      <th class="px-5">Sold</th>
      <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="sale in sales" :key="sale.id">
      <th class="text-left">{{ sale.theater }}</th>
      <td class="text-center">{{ parseFloat(sale.price).toFixed(2) }}</td>
      <td class="text-center">{{ parseInt(sale.tickets_sold) }}</td>
      <td class="text-center">${{ parseFloat(sale.total_revenue).toFixed(2) }}</td>
    </tr>
    </tbody>
  </table>
</template>