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
  <table>
    <caption class="caption-bottom p-2.5">Flick Facts Franchise</caption>
    <thead>
    <tr>
      <th>Movie</th>
      <th class="px-5">Sold</th>
      <th class="px-5">Total</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="movie in movies" :key="movie.id">
      <th class="text-left underline cursor-pointer" @click="fetchMovie(movie.id)">{{ movie.title }}</th>
      <td class="text-center">{{ parseInt(movie.quantity) }}</td>
      <td class="text-center">${{ parseFloat(movie.total_revenue).toFixed(2) }}</td>
    </tr>
    </tbody>
  </table>
</template>