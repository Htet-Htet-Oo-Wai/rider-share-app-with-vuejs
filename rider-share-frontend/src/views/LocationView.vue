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
          <h1
            class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white"
          >
            Where are we going?
          </h1>
          <form class="space-y-4 md:space-y-6" action="#">
            <div>
              <GMapAutocomplete
                @place_changed="handleLocationChanged"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Enter Destination"
                required=""
              >
              </GMapAutocomplete>
            </div>
            <div class="text-center lg:text-left">
              <button
                type="button"
                @click.prevent="handleSelectLocation"
                data-te-ripple-init
                data-te-ripple-color="light"
                class="inline-block w-full rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-neutral-900 hover:shadow-lg focus:bg-neutral-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-lg"
              >
                Find a ride
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { useLocationStore } from "@/stores/location";
import { useRouter } from "vue-router";
const router = useRouter();
const location = useLocationStore();
const handleLocationChanged = (e) => {
  console.log("handleLocationChanged", e);
  location.$patch({
    destination: {
      name: e.name,
      address: e.formatted_address,
      geometry: {
        lat: e.geometry.location.lat(),
        lng: e.geometry.location.lng(),
      },
    },
  });
};
const handleSelectLocation = () => {
  if (location.destination.name != "") {
    router.push({
      name: "map",
    });
  }
};
</script>
<style>
</style>
