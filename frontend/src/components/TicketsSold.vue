<script setup>
const props = defineProps({
  sales: Array
});
</script>

<template>
  <div class="flex-grow flex flex-col">
    <div class="grid grid-cols-4">
      <div class="p-2.5 font-bold">Movie</div>
      <div class="p-2.5 text-center font-bold">Price</div>
      <div class="p-2.5 text-center font-bold">Sold</div>
      <div class="p-2.5 text-center font-bold">Total</div>
    </div>

    <!-- Body with scrollable content -->
    <div class="flex-grow flex overflow-auto">
      <!-- Grid container for the body -->
      <div class="flex-grow">
        <!-- Loop over the tickets -->
        <div v-for="sale in sales" :key="sale.id" class="grid grid-cols-4 row">
          <div class="text-left">
            {{ sale.movie }}
          </div>
          <div class="text-center">
            ${{ sale.price ? parseFloat(sale.price).toFixed(2) : '0.00' }}
          </div>
          <div class="text-center">
            {{ sale.tickets_sold ? parseInt(sale.tickets_sold) : '0' }}
          </div>
          <div class="text-center">
            ${{ sale.total_revenue ? parseFloat(sale.total_revenue).toFixed(2) : '0' }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Alternating row colors */
.row:nth-child(even) {
  background-color: #f0f0f0; /* Light gray */
}

.row:nth-child(odd) {
  background-color: #ffffff; /* White */
}
</style>