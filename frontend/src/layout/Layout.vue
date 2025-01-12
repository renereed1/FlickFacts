<script setup>

import {computed} from "vue";
import {useRoute} from "vue-router";

const route = useRoute();

const isReportingActive = computed(() => {
  return route.name === "reporting" || route.name === "home";
});
</script>

<template>

  <main class="rounded-md h-screen flex flex-col">

    <div class="mt-2.5 ml-2.5">
      <div class="">Flick Facts Franchise</div>

      <div class="space-x-10 text-center">
        <router-link
            :class="[
            'px-4 py-2',
            isReportingActive ? 'border px-4 py-2 rounded-md' : ''
          ]"
            :to="{ name: 'reporting' }"
        >
          Reporting
        </router-link>
        <router-link :to="{name: 'theaters'}" active-class="border px-4 py-2 rounded-md" class="px-4 py-2">Theaters
        </router-link>
        <router-link :to="{name: 'movies'}" active-class="border px-4 py-2 rounded-md" class="px-4 py-2">Movies
        </router-link>
      </div>
    </div>

    <div class="flex-grow flex m-2.5 overflow-hidden">
      <slot></slot>
    </div>
  </main>

</template>