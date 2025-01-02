<script setup>

const props = defineProps({
  movies: Array
});

const emit = defineEmits(['fetchMovie']);

const fetchMovie = (movieId) => {
  emit('fetchMovie', movieId);
};

</script>

<template>
  <div class="flex-grow flex flex-col">
    <div class="grid grid-cols-3 row">
      <div class="p-2.5 font-bold">Movie</div>
      <div class="p-2.5 text-center font-bold">Sold</div>
      <div class="p-2.5 text-center font-bold">Total</div>
    </div>

    <div class="flex-grow flex overflow-auto my-2.5">

      <!-- Body with scrollable content -->
      <div class="flex-grow">
        <div v-for="movie in movies" :key="movie.id" class="grid grid-cols-3 row">
          <div class="text-left cursor-pointer underline" @click="fetchMovie(movie.id)">
            {{ movie.title }}
          </div>
          <div class="text-center">
            {{ movie.quantity ? parseInt(movie.quantity) : '0' }}
          </div>
          <div class="text-center">
            ${{ movie.total_revenue ? parseFloat(movie.total_revenue).toFixed(2) : '0.00' }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add alternating row colors */
.row:nth-child(even) {
  background-color: #f0f0f0; /* Light gray */
}

.row:nth-child(odd) {
  background-color: #ffffff; /* White */
}
</style>
