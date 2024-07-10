<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Driving to your passenger...</h1>
     <div>
        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
            <div class="bg-white px-4 py-5 sm:p-6">
                <div>
                    <GMapMap :zoom="14" :center="location.current.geometry" ref="gMap"
                        style="width:100%; height: 256px;">
                        <GMapMarker :position="location.current.geometry" :icon="currentIcon" />
                        <GMapMarker :position="location.destination.geometry" :icon="destinationIcon" />
                    </GMapMap>
                </div>
                <div class="mt-2">
                    <p class="text-xl">Going to <strong>pick up a passenger</strong></p>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                <button v-if="trip.is_started"
                        class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
                        Complete Trip
                    </button>
                    <button v-else
                        class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
                        Start Trip
                    </button>
            </div>
        </div>
      </div>
    </div>
</template>

<script setup>
import { useLocationStore } from '@/stores/location';
import { onMounted, ref, onUnmounted } from 'vue';
import { useTripStore } from '@/stores/trip';
import { http } from '@/helpers/http';
import axios from 'axios';
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

const location = useLocationStore()
const trip = useTripStore()

const gMap = ref(null);
const intervalRef = ref(null);

const currentIcon = {
    url: 'https://openmoji.org/data/color/svg/1F698.svg',
    scaledSize: {
        width: 54,
        height: 54
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
    .then((response) => {})
    .catch((error) => {
        console.error(error)
    })
}

onMounted(() => {
    gMap.value.$mapPromise.then((mapObject) => {
       updateMapBounds(mapObject);

       intervalRef.value = setInterval(async () => {
           await location.updateCurrentLocation();

           // ažuriranje vozačeve pozicije u bazi
           broadcastDriverLocation() 

           updateMapBounds(mapObject);
       }, 5000)
    })
})

onUnmounted(() => {
    clearInterval(intervalRef.value)
    intervalRef.value = null
})

</script>