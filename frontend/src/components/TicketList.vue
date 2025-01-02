<script setup>
const props = defineProps({
  tickets: Array
});
</script>

<template>
  <div class="flex-grow flex flex-col">
    <div class="grid grid-cols-4">
      <div class="p-2.5 font-bold">Movie</div>
      <div class="p-2.5 text-center font-bold">Price</div>
      <div class="p-2.5 text-center font-bold">Total</div>
      <div class="p-2.5 text-center font-bold">Available</div>
    </div>

    <!-- Body with scrollable content -->
    <div class="flex-grow flex overflow-auto">
      <!-- Grid container for the body -->
      <div class="flex-grow">
        <!-- Loop over the tickets -->
        <div v-for="ticket in tickets" :key="ticket.id" class="grid grid-cols-4 row">
          <div class="text-left">
            {{ ticket.movie_title }}
          </div>
          <div class="text-center">
            ${{ ticket.price ? parseFloat(ticket.price).toFixed(2) : '0.00' }}
          </div>
          <div class="text-center">
            {{ ticket.total ? parseInt(ticket.total) : '0' }}
          </div>
          <div class="text-center">
            {{ ticket.available ? parseInt(ticket.available) : '0' }}
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