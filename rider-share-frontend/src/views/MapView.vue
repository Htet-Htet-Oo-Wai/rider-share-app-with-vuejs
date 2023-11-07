<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto min-h-screen lg:py-0">
      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo" />
        Rider Share App
      </a>
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <h1 class="text-center text-xl dark:text-white font-semibold my-4">Here's your trip</h1>
        <div class="mb-4">
          <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
            <div class="bg-white px-4 py-5 sm:p-6 dark:bg-gray-800">
              <div>
                <GMapMap v-if="location.destination.name !== ''" :zoom="11" :center="location.destination.geometry" ref="gMap" style="width: 100%; height: 256px;">
                </GMapMap>
              </div>
              <div class="mt-2">
                <p class="text-xl dark:text-white">Going to <strong>{{ location.destination.name }}</strong></p>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6 dark:bg-gray-800">
              <button @click="handleConfirmTrip" class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
                Let's Go!</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { useLocationStore } from '@/stores/location'
import { useTripStore } from '@/stores/trip'
import http from '@/helpers/http'
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router'

const location = useLocationStore()
const trip = useTripStore()
const router = useRouter()

const gMap = ref(null)

const handleConfirmTrip = () => {
  http().post('/api/trip', {
    origin: location.current.geometry,
    destination: location.destination.geometry,
    destination_name: location.destination.name
  })
    .then((response) => {
      console.log(response.data)
      trip.$patch(response.data)
      router.push({
        name: 'trip'
      })
    })
    .catch((error) => {
      console.error(error)
    })
}

onMounted(async () => {
  // does the user have a location set?
  if (location.destination.name === '') {
    router.push({
      name: 'location'
    })
  }

  // lets get the users current location
  await location.updateCurrentLocation()

  // draw a path on the map
  gMap.value.$mapPromise.then((mapObject) => {

    let currentPoint = new google.maps.LatLng(location.current.geometry),
      destinationPoint = new google.maps.LatLng(location.destination.geometry),
      directionsService = new google.maps.DirectionsService,
      directionsDisplay = new google.maps.DirectionsRenderer({
        map: mapObject
      })

    directionsService.route({
      origin: currentPoint,
      destination: destinationPoint,
      avoidTolls: false,
      avoidHighways: false,
      travelMode: google.maps.TravelMode.DRIVING
    }, (res, status) => {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(res)
      } else {
        console.error(status)
      }
    })
  })
})
</script>