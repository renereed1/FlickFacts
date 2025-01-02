<script setup>
import {ref} from 'vue';
import axios from 'axios';
import {toast} from 'vue3-toastify'
import LoadingSpinner from "@/components/LoadingSpinner.vue";

const apiEndpoint = import.meta.env.VITE_API_BASE_URL;
const newTicket = ref({
  movieId: '',
  price: '',
  total: '',
});

const emit = defineEmits(['ticketAdded']);

const props = defineProps({
  movies: {
    type: Array,
    required: true
  },
  theater: {
    type: Object,
    required: true,
    validator(value) {
      return (
          value.hasOwnProperty('id') && typeof value.id === 'string' &&
          value.hasOwnProperty('name') && typeof value.name === 'string'
      )
    }
  }
})

const loading = ref(false);

const handleSubmit = () => {
  loading.value = true;
  const url = `${apiEndpoint}/theaters/${props.theater.id}/tickets`

  axios.post(url, newTicket.value)
      .then(response => {
        emit('ticketAdded');
        newTicket.value.price = '';
        newTicket.value.total = '';
        toast.success('Ticket Added!')
      })
      .catch(error => {
        if (error.response) {
          if (error.response.status === 400) {
            alert(error.response.data.error);
          } else {
            console.error('Error:', error.response.status, error.response.data);
          }
        } else {
          console.error('Error:', error.message);
        }
      })
      .finally(() => {
        loading.value = false;
      });
};
</script>

<template>
  <form action="#" class="space-y-5" @submit.prevent="handleSubmit">
    <div>
      <select id="movie" v-model="newTicket.movieId" class="border p-2.5 rounded-md w-full" name="movie">
        <option disabled selected value="">Select Movie</option>
        <option v-for="movie in movies" :key="movie.id" :value="movie.id">{{ movie.title }}</option>
      </select>
    </div>

    <div>
      <input id="price" v-model="newTicket.price" class="border p-2.5 rounded-md w-full" min="1"
             name="price"
             placeholder="Price"
             step="0.01"
             type="number">
    </div>

    <div>
      <input id="totalAvailable" v-model="newTicket.total" class="border p-2.5 rounded-md w-full"
             min="1"
             name="totalAvailable" placeholder="Total Available"
             type="number">
    </div>

    <button class="border p-2.5 rounded-md w-full flex items-center justify-center gap-2.5" type="submit">Add Ticket to
      [ {{ theater.name }} ]
      <LoadingSpinner
          v-if="loading"
          size="w-4 h-4"/>
    </button>
  </form>
</template>