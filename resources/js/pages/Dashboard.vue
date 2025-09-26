<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const joinCode = ref('');

const createGame = () => {
    router.post('/games/create');
};

const joinGame = () => {
    if (!joinCode.value) return;
    router.post('/games/join', { code: joinCode.value });
};
</script>

<template>
    <Head title="Game Lobby" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-col items-center justify-center gap-6 p-8">
            <!-- Create Game -->
            <button
                class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700"
                @click="createGame"
            >
                Create Game
            </button>

            <!-- Join Game -->
            <div class="flex gap-3">
                <input
                    v-model="joinCode"
                    class="rounded-lg border px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none"
                    placeholder="Enter game code"
                    type="text"
                />
                <button
                    class="rounded-lg bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700"
                    @click="joinGame"
                >
                    Join
                </button>
            </div>
        </div>
    </AppLayout>
</template>
