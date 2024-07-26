<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Upload Registration Certificate (Only Scanned Image)</h1>
        <form @submit.prevent="handleUpload">
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white px-4 py-5 sm:p-6">
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700">Upload Certificate Image (Second and Third Page)</label>
                        <input type="file" @change="onFileChange($event)" accept="image/*" class="mt-1 block w-full" />
                        <img v-if="imageUrl" :src="imageUrl" alt="Registration Certificate" class="mt-2" />
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <button type="submit" :disabled="!file"
                        class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
                        Upload
                    </button>
                </div>
            </div>
        </form>
        <div v-if="loading" class="mt-4 bg-blue-100 text-blue-800 px-4 py-2 rounded">
            Processing image, please wait...
        </div>
        <div v-if="successMessage" class="mt-4 bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="mt-4 bg-red-100 text-red-800 px-4 py-2 rounded">
            {{ errorMessage }}
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { http } from '@/helpers/http'
import { useRouter } from 'vue-router'

const router = useRouter()

const file = ref(null)
const imageUrl = ref(null)
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const onFileChange = (event) => {
    const selectedFile = event.target.files[0]
    file.value = selectedFile
    imageUrl.value = URL.createObjectURL(selectedFile)
}

const handleUpload = async () => {
    loading.value = true
    const formData = new FormData()
    formData.append('file', file.value)

    try {
        const response = await http().post('/api/upload-registration-certificate', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        loading.value = false
        successMessage.value = response.data.message
        setTimeout(() => {
            router.push({ name: 'standby' })
        }, 3000)
    } catch (error) {
        loading.value = false
        if (error.response) {
            errorMessage.value = error.response.data.error
        } else {
            errorMessage.value = 'An error occurred while uploading the image.'
        }
    }
}
</script>
