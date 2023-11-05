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
      <div
        class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700"
      >
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <div class="grid grid-cols-6 gap-4">
            <div class="col-start-1 col-end-4 ...">
              <button
                type="button"
                @click.prevent="handleStartDriving"
                data-te-ripple-init
                data-te-ripple-color="light"
                class="inline-block w-full rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-neutral-900 hover:shadow-lg focus:bg-neutral-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-lg"
              >
                Start Driving
              </button>
            </div>
            <div class="col-end-7 col-span-3 ...">
              <button
                @click.prevent="handleFindRide"
                type="button"
                data-te-ripple-init
                data-te-ripple-color="light"
                class="inline-block w-full rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-neutral-900 hover:shadow-lg focus:bg-neutral-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-lg"
              >
                Find a drive
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { useRouter } from "vue-router";
import http from "@/helpers/http.js";

const router = useRouter();
const handleStartDriving = () => {
  http()
    .get("/api/driver")
    .then((response) => {
      if (response.data.driver) {
        router.push({
          name: "standBy",
        });
      } else {
        router.push({
          name: "driver",
        });
      }
    });
};
const handleFindRide = () => {
  router.push({
    name: "location",
  });
};
</script>
<style>
</style>
