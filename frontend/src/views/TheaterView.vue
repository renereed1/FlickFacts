<script setup>

import Layout from "@/layout/Layout.vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import TheaterList from "@/components/TheaterList.vue";
import LoadingSpinner from "@/components/LoadingSpinner.vue";
import TheaterForm from "@/components/TheaterForm.vue";
import TicketsSold from "@/components/TicketsSold.vue";
import SellTicketForm from "@/components/SellTicketForm.vue";
import AddTicketForm from "@/components/AddTicketForm.vue";
import TicketList from "@/components/TicketList.vue";

const apiEndpoint = import.meta.env.VITE_API_BASE_URL;

const module = ref('sell-ticket');
const showRightSide = ref(false);

const newTheater = ref({
  name: ''
})

const theaters = ref([]);
const totalRevenue = ref("0.00");
const theater = ref({});
const movies = ref([]);
const tickets = ref([]);
const sales = ref([]);

const newTicket = ref({
  movieId: '',
  price: '',
  total: ''
})

const loadingTickets = ref(false);
const loadingTicketsSold = ref(false);
const loadingTheaters = ref(false);
const loadingTheater = ref(false);

const newSale = ref({
  movieId: '',
  quantity: '',
});

const fetchTheaters = () => {
  const url = `${apiEndpoint}/theaters`;
  loadingTheaters.value = true;

  axios.get(url)
      .then(response => {
        theaters.value = response.data.filter(theater => theater.name !== "Total"); // Filter out the total row
        totalRevenue.value = response.data.find(theater => theater.name === "Total")?.revenue || 0;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      })
      .finally(() => {
        loadingTheaters.value = false;
      });
};

const fetchTickets = () => {
  const url = `${apiEndpoint}/theaters/${theater.value.id}/tickets`;
  loadingTickets.value = true;

  axios.get(url)
      .then(response => {
        tickets.value = response.data;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      })
      .finally(() => {
        loadingTickets.value = false;
      });
};

const handleTheaterCreated = () => {
  fetchTheaters();
};

const fetchTheater = (theaterId) => {

  const url = `${apiEndpoint}/theaters/${theaterId}`;

  loadingTheater.value = true

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
        loadingTheater.value = false;
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

const handleTicketAdded = () => {
  fetchTickets();
}

const handleTicketsSold = () => {
  fetchTheaters();
  fetchTickets();
  fetchSells();
};

const addTicket = () => {
  module.value = 'add-ticket'
}

const fetchSells = () => {

  const url = `${apiEndpoint}/theaters/${theater.value.id}/movie-sales`;
  loadingTicketsSold.value = true

  axios.get(url)
      .then(response => {
        sales.value = response.data;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      })
      .finally(() => {
        loadingTicketsSold.value = false
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

      <TheaterForm @theaterCreated="handleTheaterCreated"/>

      <h3>Theaters</h3>
      <hr>

      <!-- Show loading spinner while data is being fetched -->
      <LoadingSpinner v-if="loadingTheaters"/>

      <!-- Show the TheaterList component when data is available -->
      <TheaterList
          v-else
          :theaters="theaters"
          :totalRevenue="totalRevenue"
          @fetchTheater="fetchTheater"
      />

    </div>

    <!-- Spinner while loading -->
    <LoadingSpinner v-if="loadingTheater"/>

    <div v-else-if="showRightSide" class="flex-1 border p-2.5 space-y-5">
      <h3 class="text-xl">{{ theater.name }}</h3>
      <div class="space-x-10 text-center">
        <a :class="[module === 'sell-ticket' ? 'border p-2.5 rounded-md' : 'p-2.5']" href="#"
           @click="module = 'sell-ticket'">Sell
          Tickets</a>
        <a :class="[module === 'add-ticket' ? 'border p-2.5 rounded-md' : 'p-2.5']" href="#"
           @click="addTicket">Add
          Tickets</a>
      </div>

      <div v-if="module === 'sell-ticket'">

        <SellTicketForm :theater="{id: theater.id, name: theater.name}"
                        :tickets="tickets"
                        @ticketSold="handleTicketsSold"/>


        <div class="flex flex-col gap-10 my-5">

          <h3>Tickets Sold</h3>
          <LoadingSpinner v-if="loadingTicketsSold"/>
          <TicketsSold v-else :sales="sales"/>

        </div>
      </div>

      <div v-else-if="module === 'add-ticket'">

        <AddTicketForm :movies="movies"
                       :theater="{id: theater.id, name: theater.name}"
                       @ticketAdded="handleTicketAdded"/>

        <div class="flex flex-col gap-10 my-5">
          <h3>Tickets</h3>

          <LoadingSpinner v-if="loadingTickets"/>
          <TicketList v-else :tickets="tickets"/>
        </div>
      </div>
    </div>

  </Layout>
</template>