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
const loading = ref(false);

const newMovie = ref({
  title: '',
  description: ''
})

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
    <div class="w-1/2 border p-2.5 space-y-5">

      <MovieForm @movieCreated="handleMovieCreated"/>
      <div class="space-y-5">

        <h3>Movies</h3>
        <hr>

        <LoadingSpinner v-if="loadingMovies"/>
        <MovieList
            v-else
            :movies="movies"
            @fetchMovie="fetchMovie"/>

      </div>

    </div>

    <div v-if="showRightSide" class="flex-1 border p-2.5 space-y-5">
      <div>
        <h3 class="text-xl">{{ movie.title }}</h3>
      </div>

      <div class="flex flex-col gap-10 my-5">

        <h3>Tickets Sold</h3>

        <LoadingSpinner v-if="loadingTheaterSales"/>
        <TheaterSalesList v-else :sales="sales"/>

      </div>
    </div>

    <!-- Spinner while loading -->
    <div v-else-if="loading" class="flex flex-1 justify-center items-center space-x-2">
      <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
           fill="none" viewBox="0 0 100 101" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
            fill="currentColor"/>
        <path
            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
            fill="currentFill"/>
      </svg>
    </div>
  </Layout>

</template>