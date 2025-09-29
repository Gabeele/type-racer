<script lang="ts" setup>
import Layout from '@/layouts/AuthLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    game: Game;
    players: any[];
}>();
</script>

<template>
    <Layout>
        <Head title="Lobby" />

        <div class="flex flex-col items-center justify-center gap-6 p-8">
            <h1 class="text-2xl font-bold">Game Lobby</h1>
            <p class="text-gray-600">
                Game Code: <span class="font-mono">{{ game.code }}</span>
            </p>

            <div class="w-full max-w-md rounded-lg border p-4">
                <h2 class="mb-2 font-semibold">Players</h2>
                <ul class="space-y-2">
                    <li
                        v-for="player in players"
                        :key="player.id"
                        class="rounded bg-gray-100 px-3 py-2 text-black"
                    >
                        <span>
                            <img
                                alt="Player Avatar"
                                src="{{ player.avatar }}"
                            />
                            {{ player.name }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </Layout>
</template>
