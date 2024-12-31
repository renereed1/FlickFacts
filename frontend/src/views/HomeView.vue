<script setup>

import {ref} from "vue";
import axios from "axios";
import Layout from "@/layout/Layout.vue";

const when = ref(null);

const reporting = ref({
  theater: '',
  revenue: '',
  date: '',
});

const error = ref(null);

const apiEndpoint = import.meta.env.VITE_API_BASE_URL;

const handleSubmit = () => {
  const url = `${apiEndpoint}/highest-sales?when=${when.value}`;
  error.value = null;

  axios.get(url)
      .then(response => {
        if (response.status === 200 && (!response.data || Object.keys(response.data).length === 0)) {
          error.value = 'What a sad day! There were no sales on 05/12/2022'
        }
        const data = response.data;
        reporting.value.theater = data.theater_name;
        reporting.value.revenue = parseFloat(data.total_sales).toFixed(2);
        reporting.value.date = when.value;
      })
      .catch(error => {
        console.log('Error: ', error.message);
      })

  console.log('URL: ', url);
};

</script>

<template>

  <Layout>

  </Layout>
  
</template>
