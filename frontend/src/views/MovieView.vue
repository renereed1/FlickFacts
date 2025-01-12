<script setup>

import Layout from "@/layout/Layout.vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import MovieForm from "@/components/MovieForm.vue";
import MovieList from "@/components/MovieList.vue";
import LoadingSpinner from "@/components/LoadingSpinner.vue";
import TheaterSalesList from "@/components/TheaterSalesList.vue";

const apiEndpoint = import.meta.env.VITE_API_BASE_URL;
const sales = ref([]);
const showRightSide = ref(false);

const loadingTheaterSales = ref(false);
const loadingMovie = ref(false);
const loadingMovies = ref(false);

const movie = ref({});
const movies = ref([]);

const fetchMovies = () => {
  const url = `${apiEndpoint}/movies`;

  loadingMovies.value = true;
  axios.get(url)
      .then(response => {
        movies.value = response.data;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      })
      .finally(() => {
        loadingMovies.value = false;
      });
};

const fetchMovie = (movieId) => {
  const url = `${apiEndpoint}/movies/${movieId}`;

  loadingMovie.value = true;

  axios.get(url)
      .then(response => {
        movie.value = response.data;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      })
      .finally(() => {
        fetchSells(movie.value.id);
        loadingMovie.value = false;
        showRightSide.value = true;
      });
};

const fetchSells = () => {

  const url = `${apiEndpoint}/movies/${movie.value.id}/theater-sales`;

  loadingTheaterSales.value = true;

  axios.get(url)
      .then(response => {
        sales.value = response.data;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      })
      .finally(() => {
        loadingTheaterSales.value = false;
      })
};

const handleMovieCreated = () => {
  fetchMovies();
}

onMounted(() => {
  fetchMovies();
})

</script>

<template>
  <Layout>
    <div class="flex flex-col p-2.5 space-y-5 w-1/2">

      <MovieForm @movieCreated="handleMovieCreated"/>

      <h3>Movies</h3>
      <hr>

      <LoadingSpinner v-if="loadingMovies"/>

      <div v-else
           class="flex-grow flex overflow-hidden">
        <MovieList
            :movies="movies"
            @fetchMovie="fetchMovie"/>
      </div>

    </div>

    <!-- Spinner while loading -->
    <LoadingSpinner v-if="loadingMovie"/>

    <div v-else-if="showRightSide"
         class="flex-grow flex flex-col border p-2.5 space-y-5">
      <h3 class="text-xl">{{ movie.title }}</h3>

      <div class="flex-grow flex flex-col gap-2.5 overflow-auto">

        <h3 class="mt-10 font-bold">Tickets Sold</h3>

        <LoadingSpinner v-if="loadingTheaterSales"/>

        <div v-else
             class="flex-grow flex overflow-hidden">
          <TheaterSalesList :sales="sales"/>
        </div>

      </div>
    </div>

  </Layout>

</template>