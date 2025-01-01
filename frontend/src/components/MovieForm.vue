<script setup>
import {ref} from 'vue';
import axios from 'axios';
import {toast} from 'vue3-toastify'
import LoadingSpinner from "@/components/LoadingSpinner.vue";

const apiEndpoint = import.meta.env.VITE_API_BASE_URL;
const newMovie = ref({
  title: '',
  description: ''
});

const emit = defineEmits(['movieCreated']);

const loading = ref(false);

const handleSubmit = () => {
  loading.value = true;
  const url = `${apiEndpoint}/movies`;

  axios.post(url, newMovie.value)
      .then(response => {
        emit('movieCreated');
        newMovie.value.title = '';
        newMovie.value.description = '';
        toast.success('Movie successfully created!')
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
  <form class="space-y-5" @submit.prevent="handleSubmit">
    <div>
      <input v-model="newMovie.title" class="border p-2.5 w-full rounded-md" placeholder="Enter new Movie title"
             type="text">
    </div>
    <div>
            <textarea id="description" v-model="newMovie.description" class="border p-2.5 rounded-md w-full resize-none"
                      cols="30"
                      name="description"
                      placeholder="Description"
                      rows="3"></textarea>
    </div>

    <button class="border p-2.5 rounded-md w-full flex items-center justify-center gap-2.5" type="submit">Create Movie
      <LoadingSpinner
          v-if="loading"
          size="w-4 h-4"/>
    </button>
  </form>
</template>