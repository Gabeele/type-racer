<script lang="ts" setup>
import Layout from '@/layouts/AuthLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';

defineProps<{
    game: Game;
    players: any[];
}>();

const isGameStart = ref(false);

function startGame() {
    console.log('Starting game...');
    isGameStart.value = true;
}

function quitGame() {
    console.log('Quiting game...');
    isGameStart.value = false;
}
</script>

<template>
    <Layout>
        <div v-if="!isGameStart">
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
                            <span class="flex items-center gap-2">
                                <img
                                    :src="player.avatar"
                                    alt="Player Avatar"
                                    class="inline-block h-15 w-15 rounded-full"
                                />
                                <span class="font-bold">{{ player.name }}</span>
                            </span>
                        </li>
                    </ul>
                </div>
                <Button @click="startGame()">Everyone here?</Button>
            </div>
        </div>

        <!--   Extract this to a component!     -->
        <div v-if="isGameStart">
            <Button @click="quitGame">I hate this game!</Button>
        </div>
    </Layout>
</template>
