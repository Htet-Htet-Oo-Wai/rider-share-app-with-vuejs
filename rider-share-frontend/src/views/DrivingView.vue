<template>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto min-h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo" />
                Rider Share App
            </a>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <h1 class="text-center dark:text-white font-semibold my-4">{{ title }}</h1>
                <div class="mb-4">
                    <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left" v-if="!trip.is_complete">
                        <div class="bg-white px-4 py-5 sm:p-6 dark:bg-gray-800">
                            <div>
                                <GMapMap :zoom="14" :center="location.current.geometry" ref="gMap" style="width:100%; height: 256px;">
                                    <GMapMarker :position="location.current.geometry" :icon="currentIcon" />
                                    <GMapMarker :position="location.destination.geometry" :icon="destinationIcon" />
                                </GMapMap>
                            </div>
                            <div class="mt-2">
                                <p class="text-xl dark:text-white">Going to <strong>pick up a passanger</strong></p>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6 dark:bg-gray-800">
                            <button v-if="trip.is_started" @click="handleCompleteTrip" class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
                                Complete Trip</button>
                            <button v-else @click="handlePassengerPickedUp" class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
                                Passenger Picked Up</button>
                        </div>
                    </div>
                    <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left" v-else>
                        <div class="bg-white px-4 py-5 sm:p-6">
                            <Tada />
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
import { useRouter } from 'vue-router'
import { ref, onMounted, onUnmounted } from 'vue'
import http from '@/helpers/http'
import Tada from '@/components/Tada.vue'

const location = useLocationStore()
const trip = useTripStore()
const router = useRouter()

const gMap = ref(null)
const intervalRef = ref(null)

const title = ref('Driving to passenger...')

const currentIcon = {
    url: 'https://openmoji.org/data/color/svg/1F698.svg',
    scaledSize: {
        width: 32,
        height: 32
    }
}

const destinationIcon = {
    url: 'https://openmoji.org/data/color/svg/1F920.svg',
    scaledSize: {
        width: 24,
        height: 24
    }
}

const updateMapBounds = (mapObject) => {
    let originPoint = new google.maps.LatLng(location.current.geometry),
        destinationPoint = new google.maps.LatLng(location.destination.geometry),
        latLngBounds = new google.maps.LatLngBounds()

    latLngBounds.extend(originPoint)
    latLngBounds.extend(destinationPoint)

    mapObject.fitBounds(latLngBounds)
}

const broadcastDriverLocation = () => {
    http().post(`/api/trip/${trip.id}/location`, {
        driver_location: location.current.geometry
    })
        .then((response) => { })
        .catch((error) => {
            console.error(error)
        })
}

const handlePassengerPickedUp = () => {
    http().post(`/api/trip/${trip.id}/start`)
        .then((response) => {
            title.value = 'Travelling to destination...'
            location.$patch({
                destination: {
                    name: response.data.destination_name,
                    geometry: response.data.destination
                }
            })
            trip.$patch(response.data)
        })
        .catch((error) => {
            console.error(error)
        })
}

const handleCompleteTrip = () => {
    http().post(`/api/trip/${trip.id}/end`)
        .then((response) => {
            title.value = 'Trip completed!'

            trip.$patch(response.data)

            setTimeout(() => {
                trip.reset()

                router.push({
                    name: 'standBy'
                })
            }, 3000)
        })
        .catch((error) => {
            console.error(error)
        })
}

onMounted(() => {
    gMap.value.$mapPromise.then((mapObject) => {
        updateMapBounds(mapObject)

        intervalRef.value = setInterval(async () => {
            // update the driver's current position and update map bounds
            await location.updateCurrentLocation()

            // update the driver's position in the database
            broadcastDriverLocation()

            updateMapBounds(mapObject)
        }, 5000)
    })
})

onUnmounted(() => {
    clearInterval(intervalRef.value)

    intervalRef.value = null
})
</script>
