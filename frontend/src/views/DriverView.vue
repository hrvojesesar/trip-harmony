<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Driver and car details</h1>
        <form action="#" @submit.prevent="handleSaveDriver">
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white px-4 py-5 sm:p-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" v-model="driverDetails.name" placeholder="Name"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
                        <input type="text" name="surname" id="surname" v-model="driverDetails.surname" placeholder="Surname"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" name="birth_date" id="birth_date" v-model="driverDetails.birth_date" placeholder="dd. mm. gggg."
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="OIB" class="block text-sm font-medium text-gray-700">OIB</label>
                        <input type="text" name="OIB" id="OIB" v-model="driverDetails.OIB" placeholder="OIB"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="serial_number_identity_card" class="block text-sm font-medium text-gray-700">Identity Card Serial Number</label>
                        <input type="text" name="serial_number_identity_card" id="serial_number_identity_card" v-model="driverDetails.serial_number_identity_card" placeholder="Identity Card Serial Number"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="serial_number_driver_license" class="block text-sm font-medium text-gray-700">Driver License Serial Number</label>
                        <input type="text" name="serial_number_driver_license" id="serial_number_driver_license" v-model="driverDetails.serial_number_driver_license" placeholder="Driver License Serial Number"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="serial_number_health_card" class="block text-sm font-medium text-gray-700">Health Card Serial Number</label>
                        <input type="text" name="serial_number_health_card" id="serial_number_health_card" v-model="driverDetails.serial_number_health_card" placeholder="Health Card Serial Number"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="Car_name" class="block text-sm font-medium text-gray-700">Car Name (Big Letters)</label>
                        <input type="text" name="Car_name" id="Car_name" v-model="driverDetails.Car_name" placeholder="Car Name (Big Letters)"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="Car_model" class="block text-sm font-medium text-gray-700">Car Model (Big Letters)</label>
                        <input type="text" name="Car_model" id="Car_model" v-model="driverDetails.Car_model" placeholder="Car Model (Big Letters)"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="Car_color" class="block text-sm font-medium text-gray-700">Car Color (Big Letters)</label>
                        <input type="text" name="Car_color" id="Car_color" v-model="driverDetails.Car_color" placeholder="Car Color (Big Letters)"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                    <div class="mt-2">
                        <label for="registration_mark" class="block text-sm font-medium text-gray-700">Registration Mark</label>
                        <input type="text" name="registration_mark" id="registration_mark" v-model="driverDetails.registration_mark" placeholder="Registration Mark"
                            class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <button type="submit"
                        @click.prevent="handleSaveDriver"
                        class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">Continue</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>

import { reactive } from 'vue'
import { http } from '@/helpers/http'
import router from '../router';

const driverDetails = reactive({
    name: '',
    surname: '',
    birth_date: '',
    OIB: '',
    serial_number_identity_card: '',
    serial_number_driver_license: '',
    serial_number_health_card: '',
    Car_name: '',
    Car_model: '',
    Car_color: '',
    registration_mark: ''
})

const handleSaveDriver = () => {
    http().post('/api/driver', driverDetails)
        .then((response) => {
            router.push({
                name: 'identityCard'
            })
        })
        .catch((error) => {
            console.error(error)
        })
}

</script>
