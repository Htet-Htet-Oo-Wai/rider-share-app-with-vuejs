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
            Here's your trip!
          </h1>
          <p class="text-xl">
            Going to <strong>{{ location.destination.name }}</strong>
          </p>
          <div>
            <GMapMap
              :zoom="11"
              :center="location.destination.geometry"
              ref="gMap"
              style="width: 100%; height: 256px"
            >
            </GMapMap>
          </div>
          <div class="text-center lg:text-left">
            <button
              type="button"
              @click="handleConfirmTrip"
              data-te-ripple-init
              data-te-ripple-color="light"
              class="inline-block w-full rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-neutral-900 hover:shadow-lg focus:bg-neutral-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-lg"
            >
              Let's Go!.
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { useLocationStore } from "@/stores/location";
import axios from "axios";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import http from "@/helpers/http.js";

const location = useLocationStore();
const router = useRouter();

const gMap = ref(null);

const handleConfirmTrip = () => {
  http()
    .post("/api/trip", {
      origin: location.current.geometry,
      destination: location.destination.geometry,
      destination_name: location.destination.name,
    })
    .then((response) => {
      router.push({
        name: "trip",
      });
    })
    .catch((error) => {
      console.error(error);
    });
};

onMounted(async () => {
  //does the user have location set?
  if (location.destination.name === "") {
    router.push({
      name: "location",
    });
  }
  await location.updateCurrentLocation();

  // draw a path on the map
  gMap.value.$mapPromise.then((mapObject) => {
    let currentPoint = new google.maps.LatLng(location.current.geometry),
      destinationPoint = new google.maps.LatLng(location.destination.geometry),
      directionsService = new google.maps.DirectionsService(),
      directionsDisplay = new google.maps.DirectionsRenderer({
        map: mapObject,
      });

    directionsService.route(
      {
        origin: currentPoint,
        destination: destinationPoint,
        avoidTolls: false,
        avoidHighways: false,
        travelMode: google.maps.TravelMode.DRIVING,
      },
      (res, status) => {
        if (status == google.maps.DirectionsStatus.OK) {
          directionsDisplay.setDirections(res);
        } else {
          console.error(status);
        }
      }
    );
  });
});
</script>
<style>
</style>
