<script setup>

const props = defineProps({
  theaters: {
    type: Array,
    required: true,
  },
  totalRevenue: {
    type: String,
    required: true,
  },
})

const emit = defineEmits(['fetchTheater']);

const fetchTheater = (theaterId) => {
  // Emit the selected theater ID to the parent
  emit('fetchTheater', theaterId);
};
</script>

<template>
  <div class="space-y-5">

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
</template>