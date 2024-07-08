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
</script>