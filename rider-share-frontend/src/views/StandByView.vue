<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div
      class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"
    >
      <a
        href="#"
        class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white"
      >
        <img
          class="w-8 h-8 mr-2"
          src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg"
          alt="logo"
        />
        Rider Share App
      </a>

      <div class="pt-16">
        <h1
          class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white"
        >
          Waiting for ride request.......
        </h1>
        <div class="mt-8 flex justify-center">
          <loader />
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import Loader from "@/components/Loader.vue";
import { onMounted } from "vue";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

onMounted(() => {
  let echo = new Echo({
    broadcaster: "pusher",
    key: "7d73c0844235ea8922a0",
    cluster: "ap1",
    wsHost: '127.0.0.1:6001',
    wsPort: 6001,
    forceTLs: false,
    disableStats: true,
    enabledTransports: ["ws", "wss"],
  });

  echo.channel("drivers").listen("TripStarted", (e) => {
    console.log("TripCreated", e);
  });
});
</script>
<style>
</style>
