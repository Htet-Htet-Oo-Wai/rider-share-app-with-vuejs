<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto min-h-screen lg:py-0">
      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo" />
        Rider Share App
      </a>
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <h1 class="text-center font-semibold mb-4 pt-2 dark:text-white">{{ title }}</h1>
        <div v-if="!trip.id" class="mt-8 flex justify-center">
          <Loader />
        </div>
        <div v-else>
          <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left mb-4">
            <div class="bg-white px-4 py-5 sm:p-6 dark:bg-gray-800">
              <div>
                <GMapMap :zoom="14" :center="trip.destination" ref="gMap" style="width:100%; height: 256px;"></GMapMap>
              </div>
              <div class="mt-2">
                <p class="text-xl dark:text-white">Going to <strong>{{ trip.destination_name }}</strong></p>
              </div>
            </div>
            <div class="flex justify-between bg-gray-50 px-4 py-3 text-right sm:px-6 dark:bg-gray-800">
              <button @click="handleDeclineTrip" class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">Decline</button>
              <button @click="handleAcceptTrip" class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">Accept</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { ref } from 'vue'
import Loader from '@/components/Loader.vue'
import { onMounted } from 'vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import { useTripStore } from '@/stores/trip'
import { useLocationStore } from '@/stores/location'
import http from '@/helpers/http'
import { useRouter } from 'vue-router'

const title = ref('Waiting for ride request...')
const gMap = ref(null)
const trip = useTripStore()
const location = useLocationStore()
const router = useRouter()

const handleDeclineTrip = () => {
  trip.reset()
  title.value = 'Waiting for ride request...'
}

const handleAcceptTrip = () => {
  http().post(`/api/trip/${trip.id}/accept`, {
    driver_location: location.current.geometry
  })
    .then((response) => {
      location.$patch({
        destination: {
          name: 'Passenger',
          geometry: response.data.origin
        }
      })

      router.push({
        name: 'driving'
      })
    })
    .catch((error) => {
      console.error(error)
    })
}

onMounted(async () => {
  await location.updateCurrentLocation()

  let echo = new Echo({
    broadcaster: 'pusher',
    key: '7d73c0844235ea8922a0',
    cluster: 'ap1',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss']
  })

  echo.channel('drivers')
    .listen('TripCreated', (e) => {
      title.value = 'Ride requested:'

      trip.$patch(e.trip)
      console.log('TripCreated', e)

      setTimeout(initMapDirections, 2000)
    })
})

const initMapDirections = () => {
  gMap.value.$mapPromise.then((mapObject) => {

    let originPoint = new google.maps.LatLng(trip.origin),
      destinationPoint = new google.maps.LatLng(trip.destination),
      directionsService = new google.maps.DirectionsService,
      directionsDisplay = new google.maps.DirectionsRenderer({
        map: mapObject
      })

    directionsService.route({
      origin: originPoint,
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
}
</script>