<script setup>
import {ref} from 'vue';
import axios from 'axios';
import {toast} from 'vue3-toastify'
import LoadingSpinner from "@/components/LoadingSpinner.vue";

const apiEndpoint = import.meta.env.VITE_API_BASE_URL;
const newTheater = ref({name: ''});

const emit = defineEmits(['theaterCreated']);

const loading = ref(false);

const handleSubmit = () => {
  loading.value = true;
  const url = `${apiEndpoint}/theaters`;

  axios.post(url, newTheater.value)
      .then(response => {
        emit('theaterCreated');
        newTheater.value.name = ''
        toast.success('Theater successfully created!')
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
      <input v-model="newTheater.name" class="border p-2.5 w-full rounded-md" placeholder="Enter new Theater name"
             type="text">
    </div>

    <button class="border p-2.5 rounded-md w-full flex items-center justify-center gap-2.5" type="submit">Create Theater
      <LoadingSpinner
          v-if="loading"
          size="w-4 h-4"/>
    </button>


  </form>
</template>