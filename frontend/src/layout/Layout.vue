<script setup>

</script>

<template>

  <main class="rounded-md h-screen flex flex-col">

    <div class="space-x-10 text-center">
      <router-link :to="{name: 'reporting'}" active-class="border px-4 py-2 rounded-md" class="px-4 py-2">Reporting
      </router-link>
      <router-link :to="{name: 'theaters'}" active-class="border px-4 py-2 rounded-md" class="px-4 py-2">Theaters
      </router-link>
      <router-link :to="{name: 'movies'}" active-class="border px-4 py-2 rounded-md" class="px-4 py-2">Movies
      </router-link>
    </div>

    <div class="flex-grow flex m-2.5 overflow-hidden">
      <slot></slot>
    </div>
  </main>

</template>