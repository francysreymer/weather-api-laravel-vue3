<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { handleAxiosError } from '@/utils/axios';
import { formatDate } from '@/utils/date';
import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { Head, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import Cookies from 'js-cookie';
import { ref } from 'vue';

interface ExtendedPageProps extends InertiaPageProps {
    token?: string;
}

defineProps<{ locations: any[] }>();
const { props } = usePage<ExtendedPageProps>();
const token = ref<string | undefined>(props.token);
const isLoading = ref<{ [key: number]: boolean }>({});
const errorMessages = ref<string[]>([]);

if (token.value) {
    Cookies.set('api_token', token.value, {
        secure: true, // Ensure the cookie is only sent over HTTPS
        sameSite: 'Strict', // Prevent CSRF attacks
    });
}

const deleteForecast = async (locationId: number) => {
    isLoading.value[locationId] = true;
    errorMessages.value = [];
    try {
        await axios.delete(`/api/locations/${locationId}`, {
            headers: {
                Authorization: `Bearer ${Cookies.get('api_token')}`,
            },
        });
        location.reload();
    } catch (error) {
        errorMessages.value = handleAxiosError(error);
    } finally {
        isLoading.value[locationId] = false;
    }
};
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
                    <a href="/locations/add" class="btn btn-add"
                        >Add New Location</a
                    >
                </div>
                <div
                    class="div-table overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <table class="mt-4 w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">City, State</th>
                                <th class="px-4 py-2">Created at</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(location, index) in locations"
                                :key="index"
                                class="mb-6"
                            >
                                <td class="border px-4 py-2">
                                    {{ location.name }}
                                </td>
                                <td class="border px-4 py-2">
                                    {{ formatDate(location.created_at) }}
                                </td>
                                <td class="border px-4 py-2">
                                    <a
                                        :href="`/locations/view/${location.id}`"
                                        class="btn btn-view"
                                    >
                                        View
                                    </a>
                                    <button
                                        @click="deleteForecast(location.id)"
                                        type="button"
                                        class="btn btn-delete"
                                        :disabled="isLoading[location.id]"
                                    >
                                        <span v-if="isLoading[location.id]"
                                            >Deleting...</span
                                        >
                                        <span v-else>Delete</span>
                                    </button>
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
.div-table {
    padding: 40px;
}
.table-auto {
    max-width: 100%;
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

.btn {
    display: inline-block;
    padding: 8px 16px;
    margin: 4px;
    border: none;
    border-radius: 4px;
    text-align: center;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-view {
    background-color: #4caf50; /* Green */
    color: white;
}

.btn-view:hover {
    background-color: #45a049;
}

.btn-delete {
    background-color: #f44336; /* Red */
    color: white;
}

.btn-delete:hover {
    background-color: #da190b;
}

.btn-add {
    background-color: #007bff; /* Blue */
    color: white;
}

.btn-add:hover {
    background-color: #0056b3;
}

.text-red-500 {
    color: #f44336;
}

.flex {
    display: flex;
}

.justify-between {
    justify-content: space-between;
}

.items-center {
    align-items: center;
}

.mb-4 {
    margin-bottom: 1rem;
}
</style>
