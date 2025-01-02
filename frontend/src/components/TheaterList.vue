<script setup>

const props = defineProps({
  theaters: {
    type: Array,
    required: true,
  },
  totalRevenue: {
    type: String,
    required: true,
  },
})

const emit = defineEmits(['fetchTheater']);

const fetchTheater = (theaterId) => {
  // Emit the selected theater ID to the parent
  emit('fetchTheater', theaterId);
};
</script>

<template>
  <div class="flex-grow flex flex-col">
    <!-- Header -->
    <div class="flex justify-around">
      <div class="p-2.5 font-bold">Theater</div>
      <div class="p-2.5 text-center font-bold">Total</div>
    </div>

    <!-- Body with scrollable content -->
    <div class="flex-grow flex overflow-auto my-2.5">
      <div class="flex-grow">
        <div v-for="theater in theaters" :key="theater.id" class="grid grid-cols-[1fr,1fr,auto] row">
          <div class="text-left cursor-pointer underline" @click="fetchTheater(theater.id)">
            {{ theater.name }}
          </div>
          <div class="text-center">
            ${{ theater.revenue ? parseFloat(theater.revenue).toFixed(2) : '0.00' }}
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="p-2.5">
      <div class="flex justify-around">
        <div class="font-bold"></div>
        <div class="text-center font-bold">
          <p class="font-semibold">Total Revenue
            <span class="px-5">${{ parseFloat(totalRevenue).toFixed(2) }}</span></p>
        </div>
      </div>
      <div class="p-2.5 text-center">
        <h2 class="font-semibold">Flick Facts Franchise</h2>
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
