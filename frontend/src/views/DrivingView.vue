<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Driving to passenger...</h1>
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

            </div>
        </div>
      </div>
    </div>
</template>

<script setup>
import { useLocationStore } from '@/stores/location';
import { onMounted, ref } from 'vue';


const location = useLocationStore()

const gMap = ref(null);

const currentIcon = {
    url: 'https://openmoji.org/data/color/svg/1F698.svg',
    scaledSize: {
        width: 24,
        height: 24
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

onMounted(() => {
    gMap.value.$mapPromise.then((mapObject) => {
       updateMapBounds(mapObject);

       setInterval(async () => {
           await location.updateCurrentLocation();
           updateMapBounds(mapObject);
            console.log('Vozač: ', location.current.geometry)
            console.log('Putnik: ', location.destination.geometry)
       }, 5000)
    })
})



</script>