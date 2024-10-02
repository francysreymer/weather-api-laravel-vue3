<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { handleAxiosError } from '@/utils/axios';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Cookies from 'js-cookie';
import { ref } from 'vue';

const city = ref('');
const state = ref('');
const isLoadingSave = ref(false);
const successMessage = ref('');
const errorMessages = ref<string[]>([]);

const submit = async () => {
    successMessage.value = '';
    errorMessages.value = [];
    try {
        const data = await axios.post(
            '/api/locations',
            {
                city: city.value,
                state: state.value,
            },
            {
                headers: {
                    Authorization: `Bearer ${Cookies.get('api_token')}`,
                },
            },
        );
        successMessage.value = 'Location saved successfully!';
        console.log('francys data: ', data);
        setTimeout(() => {
            window.location.href = '/locations';
        }, 1000); // Redirect after 1 second
    } catch (error) {
        errorMessages.value = handleAxiosError(error);
    } finally {
        isLoadingSave.value = false;
    }
};
</script>

<template>
    <Head title="Add Location" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Locations & Weather Forecasts
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-4 flex items-center justify-between">
                    <a href="/locations" class="btn btn-add">Locations</a>
                </div>
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label
                                    for="city"
                                    class="block text-sm font-medium text-gray-700"
                                    >City</label
                                >
                                <input
                                    v-model="city"
                                    type="text"
                                    id="city"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>
                            <div class="mb-4">
                                <label
                                    for="state"
                                    class="block text-sm font-medium text-gray-700"
                                    >State</label
                                >
                                <input
                                    v-model="state"
                                    type="text"
                                    id="state"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>
                            <button type="submit" class="btn btn-success">
                                Save Location
                            </button>
                        </form>

                        <div v-if="isLoadingSave" class="mt-4">
                            <p>Saving location...</p>
                        </div>

                        <div v-if="successMessage" class="success-message mt-4">
                            <p>{{ successMessage }}</p>
                        </div>
                        <div
                            v-if="errorMessages.length"
                            class="error-messages mt-4"
                        >
                            <ul>
                                <li
                                    v-for="(error, index) in errorMessages"
                                    :key="index"
                                >
                                    {{ error }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.btn {
    display: inline-block;
    padding: 10px 20px;
    margin: 4px;
    border: none;
    border-radius: 4px;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    cursor: pointer;
    transition:
        background-color 0.3s ease,
        box-shadow 0.3s ease;
}

.btn-primary {
    background-color: #007bff; /* Blue */
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-primary:active {
    background-color: #004494;
    box-shadow: 0 5px #666;
    transform: translateY(4px);
}

.btn-primary:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5);
}

.btn-success {
    background-color: #28a745; /* Green */
    color: white;
}

.btn-success:hover {
    background-color: #218838;
}

.btn-success:active {
    background-color: #1e7e34;
    box-shadow: 0 5px #666;
    transform: translateY(4px);
}

.btn-success:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.5);
}

.btn:disabled {
    background-color: #cccccc;
    color: #666666;
    cursor: not-allowed;
}

.success-message {
    background-color: #d4edda; /* Light green background */
    color: #155724; /* Dark green text */
    border: 1px solid #c3e6cb; /* Green border */
    padding: 15px;
    border-radius: 4px;
    font-size: 16px;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.success-message p {
    margin: 0;
}

.error-messages {
    background-color: #f8d7da; /* Light red background */
    color: #721c24; /* Dark red text */
    border: 1px solid #f5c6cb; /* Red border */
    padding: 15px;
    border-radius: 4px;
    font-size: 16px;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.error-messages ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.error-messages li {
    margin-bottom: 10px;
}

.error-messages li:last-child {
    margin-bottom: 0;
}

.btn-add {
    padding: 8px 16px;
    background-color: #007bff; /* Blue */
    font-size: 14px;
    color: white;
}

.btn-add:hover {
    background-color: #0056b3;
}

.btn-purple {
    background-color: #6f42c1; /* Purple */
    color: white;
}

.btn-purple:hover {
    background-color: #5a32a3;
}

.btn-purple:active {
    background-color: #4a2785;
    box-shadow: 0 5px #666;
    transform: translateY(4px);
}

.btn-purple:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.5);
}
</style>
