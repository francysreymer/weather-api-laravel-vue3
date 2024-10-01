<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Forecast } from '@/types/forecast';
import { formatDate } from '@/utils/date';
import { Head } from '@inertiajs/vue3';

type Location = {
    name: string;
    forecasts: Forecast[];
};

defineProps<{ location: Location }>();
</script>

<template>
    <Head title="Locations" />

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
                <div
                    class="div-table overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="location-name">
                        {{ location.name }}
                    </div>
                    <table class="mt-4 w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Main</th>
                                <th class="px-4 py-2">Description</th>
                                <th class="px-4 py-2">Temperature</th>
                                <th class="px-4 py-2">Feels Like</th>
                                <th class="px-4 py-2">Min Temperature</th>
                                <th class="px-4 py-2">Max Temperature</th>
                                <th class="px-4 py-2">Pressure</th>
                                <th class="px-4 py-2">Humidity</th>
                                <th class="px-4 py-2">Wind Speed</th>
                                <th class="px-4 py-2">Cloudiness</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(forecast, index) in location.forecasts"
                                :key="index"
                                class="mb-6"
                            >
                                <td class="border px-4 py-2">
                                    {{ formatDate(forecast.date_forecast) }}
                                </td>
                                <td class="border px-4 py-2">
                                    <img
                                        :src="`http://openweathermap.org/img/wn/${forecast.icon}.png`"
                                        alt="Weather Icon"
                                        class="mr-4"
                                    />
                                    {{ forecast.main }}
                                </td>
                                <td class="border px-4 py-2">
                                    {{ forecast.description }}
                                </td>
                                <td class="border px-4 py-2">
                                    {{ forecast.temperature }}°C
                                </td>
                                <td class="border px-4 py-2">
                                    {{ forecast.feels_like }}°C
                                </td>
                                <td class="border px-4 py-2">
                                    {{ forecast.min_temperature }}°C
                                </td>
                                <td class="border px-4 py-2">
                                    {{ forecast.max_temperature }}°C
                                </td>
                                <td class="border px-4 py-2">
                                    {{ forecast.pressure }} hPa
                                </td>
                                <td class="border px-4 py-2">
                                    {{ forecast.humidity }}%
                                </td>
                                <td class="border px-4 py-2">
                                    {{ forecast.wind_speed }} m/s
                                </td>
                                <td class="border px-4 py-2">
                                    {{ forecast.cloudiness }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.loading {
    font-size: 18px;
    color: #555;
}

.forecasts {
    margin-top: 20px;
}

.forecast {
    background-color: #f9f9f9;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.forecast p {
    margin: 5px 0;
}

.div-table {
    padding: 40px;
    overflow-x: auto; /* Enable horizontal scrolling */
}

.table-auto {
    width: 100%;
    border-collapse: collapse;
}

.table-auto th,
.table-auto td {
    border: 1px solid #ddd;
    padding: 8px;
}

.table-auto th {
    background-color: #f2f2f2;
    text-align: left;
}

.location-name {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin: 20px 0;
    padding: 10px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}

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

.btn-add {
    padding: 8px 16px;
    background-color: #007bff; /* Blue */
    font-size: 14px;
    color: white;
}

.btn-add:hover {
    background-color: #0056b3;
}
</style>
