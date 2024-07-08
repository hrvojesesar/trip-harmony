<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Waiting for a ride request...</h1>
        <div class="mt-8 flex justify-center">
            <Loader />
        </div>
    </div>
</template>

<script setup>
import Loader from '@/components/Loader.vue';
import Echo from 'laravel-echo';
import { onMounted } from 'vue';
import Pusher from 'pusher-js';

onMounted(() => {
   let echo = new Echo({
        broadcaster: 'pusher',
        key: 'mykey',
        cluster: 'mt1',
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        disableStats: true,
        enabledTransports: ['ws', 'wss']
   });

    echo.channel('drivers')
         .listen('TripCreated', (e) => {
              console.log('TripCreated', e);
         });
});

</script>