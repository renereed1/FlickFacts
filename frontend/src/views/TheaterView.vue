<script setup>

import Layout from "@/layout/Layout.vue";
import {onMounted, ref} from "vue";
import axios from "axios";

const apiEndpoint = import.meta.env.VITE_API_BASE_URL;

const module = ref('sell-ticket');
const showRightSide = ref(false);

const newTheater = ref({
  name: ''
})

const theaters = ref([]);
const totalRevenue = ref([]);
const theater = ref({});
const movies = ref([]);
const tickets = ref([]);
const sales = ref([]);

const newTicket = ref({
  movieId: '',
  price: '',
  total: ''
})

const loading = ref(false);

const newSale = ref({
  movieId: '',
  quantity: '',
});

const fetchTheaters = () => {
  const url = `${apiEndpoint}/theaters`;

  axios.get(url)
      .then(response => {
        theaters.value = response.data.filter(theater => theater.name !== "Total"); // Filter out the total row
        totalRevenue.value = response.data.find(theater => theater.name === "Total")?.revenue || 0;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      });
};

const fetchTickets = () => {
  const url = `${apiEndpoint}/theaters/${theater.value.id}/tickets`;

  axios.get(url)
      .then(response => {
        tickets.value = response.data;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      });
};

const handleSubmit = () => {

  const url = `${apiEndpoint}/theaters`;

  axios.post(url, newTheater.value)
      .then(response => {
        fetchTheaters();
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
      });
};

const fetchTheater = (theaterId) => {

  const url = `${apiEndpoint}/theaters/${theaterId}`;

  loading.value = true

  axios.get(url)
      .then(response => {
        theater.value = response.data;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      })
      .finally(() => {
        fetchTickets();
        fetchSells(theater.value.id);
        loading.value = false;
        showRightSide.value = true;
      });
}

const fetchMovies = () => {

  const url = `${apiEndpoint}/movies`;

  axios.get(url)
      .then(response => {
        movies.value = response.data;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      });
};

const handleAddTicket = (theaterId) => {

  const url = `${apiEndpoint}/theaters/${theaterId}/tickets`

  axios.post(url, newTicket.value)
      .then(response => {
        fetchTickets();
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
      });
}

const handleSellTicket = () => {

  const url = `${apiEndpoint}/theaters/${theater.value.id}/tickets-sell`;

  axios.post(url, newSale.value)
      .then(response => {
        fetchTheaters();
        fetchTickets();
        fetchSells();
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
      });
};

const addTicket = () => {
  module.value = 'add-ticket'
}

const fetchSells = () => {

  const url = `${apiEndpoint}/theaters/${theater.value.id}/movie-sales`;

  axios.get(url)
      .then(response => {
        sales.value = response.data;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      })
};

onMounted(() => {
  fetchTheaters();
  fetchMovies();
});

</script>

<template>
  <Layout>

    <div class="border p-2.5 space-y-5 w-1/2">

      <div>
        <form action="#" @submit.prevent="handleSubmit">
          <div>
            <input v-model="newTheater.name" class="border p-2.5 w-full rounded-md" placeholder="Enter new Theater name"
                   type="text">
          </div>
        </form>
      </div>

      <div class="space-y-5">

        <h3>Theaters</h3>
        <hr>

        <table>
          <caption class="caption-bottom p-2.5">Flick Facts Franchise</caption>
          <thead>
          <tr>
            <th colspan="2">Name</th>
            <th>Total</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="theater in theaters" :key="theater.id">
            <th class="text-left cursor-pointer underline" colspan="2" @click="fetchTheater(theater.id)">{{
                theater.name
              }}
            </th>
            <td class="text-center">${{ theater.revenue ? parseFloat(theater.revenue).toFixed(2) : '0.00' }}</td>
          </tr>
          </tbody>
          <tfoot>
          <tr>
            <th colspan="2">Total Revenue</th>
            <td class="px-5 text-right">${{ parseFloat(totalRevenue).toFixed(2) }}</td>
          </tr>
          </tfoot>
        </table>

      </div>
    </div>

    <div v-if="showRightSide" class="flex-1 border p-2.5 space-y-5">
      <h3 class="text-xl">{{ theater.name }}</h3>
      <div class="space-x-10 text-center">
        <a :class="[module === 'sell-ticket' ? 'border p-2.5 rounded-md' : 'p-2.5']" href="#"
           @click="module = 'sell-ticket'">Sell
          Tickets</a>
        <a :class="[module === 'add-ticket' ? 'border p-2.5 rounded-md' : 'p-2.5']" href="#"
           @click="addTicket">Add
          Tickets</a>
      </div>

      <div v-if="module === 'sell-ticket'" class="">

        <form action="#" @submit.prevent="handleSellTicket">
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
              <button class="border p-2.5 w-full rounded-md" type="submit">Sell Ticket from [ {{
                  theater.name
                }}]
              </button>
            </div>
          </div>
        </form>


        <div class="flex flex-col gap-10 my-5">

          <h3>Tickets Sold</h3>

          <table>
            <thead>
            <tr>
              <th>Movie</th>
              <th>Price</th>
              <th class="px-5">Sold</th>
              <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="sale in sales" :key="sale.id">
              <th class="text-left">{{ sale.movie }}</th>
              <td class="text-center">{{ sale.price }}</td>
              <td class="text-center">{{ parseInt(sale.tickets_sold) }}</td>
              <td class="text-center">${{ parseFloat(sale.total_revenue).toFixed(2) }}</td>
            </tr>
            </tbody>
          </table>

        </div>
      </div>

      <div v-else-if="module === 'add-ticket'">

        <form action="#" class="space-y-5" @submit.prevent="handleAddTicket(theater.id)">
          <div>
            <select id="movie" v-model="newTicket.movieId" class="border p-2.5 rounded-md w-full" name="movie">
              <option disabled selected value="">Select Movie</option>
              <option v-for="movie in movies" :key="movie.id" :value="movie.id">{{ movie.title }}</option>
            </select>
          </div>

          <div>
            <input id="price" v-model="newTicket.price" class="border p-2.5 rounded-md w-full" min="0"
                   name="price"
                   placeholder="Price"
                   step="0.01"
                   type="number">
          </div>

          <div>
            <input id="totalAvailable" v-model="newTicket.total" class="border p-2.5 rounded-md w-full"
                   name="totalAvailable"
                   placeholder="Total Available" type="number">
          </div>

          <button class="border p-2.5 rounded-md w-full" type="submit">Add Ticket to [ {{ theater.name }} ]</button>
        </form>

        <div class="flex flex-col gap-10 my-5">
          <h3>Tickets</h3>

          <table>
            <thead>
            <tr>
              <th>Movie</th>
              <th class="px-5">Price</th>
              <th class="px-5">Total</th>
              <th class="px-5">Available</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="ticket in tickets" :key="ticket.id">
              <th class="text-left">{{ ticket.movie_title }}</th>
              <td class="text-center">${{ ticket.price }}</td>
              <td class="text-center">{{ ticket.total }}</td>
              <td class="text-center">{{ ticket.available }}</td>
            </tr>
            </tbody>
          </table>
        </div>
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