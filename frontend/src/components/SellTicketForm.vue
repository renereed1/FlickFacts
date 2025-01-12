<script setup>
import {ref} from 'vue';
import axios from 'axios';
import {toast} from 'vue3-toastify'
import LoadingSpinner from "@/components/LoadingSpinner.vue";

const apiEndpoint = import.meta.env.VITE_API_BASE_URL;
const newSale = ref({
  movieId: '',
  quantity: '',
  discountCode: '',
});

const emit = defineEmits(['ticketSold']);

const loading = ref(false);

const props = defineProps({
  tickets: {
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

const handleSubmit = () => {
  loading.value = true;
  const url = `${apiEndpoint}/theaters/${props.theater.id}/tickets-sell`;

  axios.post(url, newSale.value)
      .then(response => {
        emit('ticketSold');
        newSale.value.quantity = '';
        toast.success('Ticket(s) sold!')
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
  <form action="#" @submit.prevent="handleSubmit">
    <div class="space-y-5">
      <div>
        <select id="" v-model="newSale.movieId" class="border p-2.5 w-full rounded-md" name="">
          <option disabled selected value="">Select Movie</option>
          <option v-for="ticket in tickets" :key="ticket.id" :value="ticket.movie_id">{{
              ticket.movie_title
            }} -
            ${{ ticket.price }} -
            [
            {{ ticket.available > 0 ? `${ticket.available} tickets available` : 'Out of tickets' }}
            ]
          </option>
        </select>
      </div>

      <div>
        <input id="quantity" v-model="newSale.quantity" class="border p-2.5 w-full rounded-md" name="quantity"
               placeholder="Quantity"
               type="number">
      </div>

      <div>
        <select id="discountCode" v-model="newSale.discountCode" class="border p-2.5 w-full rounded-md"
                name="discountCode">
          <option value="">No Discount</option>
          <option value="everyone-10">Everyone (10% Discount)</option>
        </select>
      </div>

      <div>
        <button class="border p-2.5 rounded-md w-full flex items-center justify-center gap-2.5" type="submit">Sell
          Ticket from [ {{
            props.theater.name
          }}]
          <LoadingSpinner
              v-if="loading"
              size="w-4 h-4"/>
        </button>
      </div>
    </div>
  </form>
</template>