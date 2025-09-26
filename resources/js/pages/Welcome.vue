<script lang="ts" setup>
import { dashboard } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
</script>

<template>
    <Head title="Welcome">
        <link href="https://rsms.me/" rel="preconnect" />
        <link href="https://rsms.me/inter/inter.css" rel="stylesheet" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <!-- Header -->
        <header class="mb-6 w-full max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-md border border-[#19140035] px-5 py-2 text-sm font-medium text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
            </nav>
        </header>

        <!-- Main Content -->
        <div class="flex w-full items-center justify-center lg:grow">
            <main
                class="flex w-full max-w-[335px] flex-col items-center justify-center gap-8 rounded-lg bg-white p-8 text-center shadow-[0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:max-w-lg dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
            >
                <div>
                    <h1 class="mb-2 text-2xl font-semibold">Welcome</h1>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Sign in with your GitHub account to continue
                    </p>
                </div>

                <!-- GitHub Login Button -->
                <Button
                    as="a"
                    class="flex w-full items-center justify-center gap-2 rounded-md bg-black px-6 py-3 text-white hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-200"
                    href="/auth/github/redirect"
                >
                    <!-- GitHub Logo -->
                    <svg
                        class="h-5 w-5"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.387.6.113.82-.262.82-.582 0-.287-.01-1.046-.015-2.05-3.338.726-4.042-1.61-4.042-1.61-.546-1.385-1.334-1.754-1.334-1.754-1.09-.745.083-.73.083-.73 1.205.084 1.84 1.237 1.84 1.237 1.07 1.835 2.807 1.305 3.492.997.108-.776.418-1.305.762-1.605-2.665-.305-5.466-1.334-5.466-5.933 0-1.31.47-2.38 1.237-3.22-.123-.304-.536-1.527.117-3.176 0 0 1.01-.323 3.3 1.23a11.5 11.5 0 0 1 3-.404c1.02.005 2.045.138 3 .404 2.29-1.553 3.3-1.23 3.3-1.23.653 1.65.24 2.872.117 3.176.767.84 1.237 1.91 1.237 3.22 0 4.61-2.802 5.625-5.475 5.922.43.372.81 1.104.81 2.227 0 1.61-.015 2.905-.015 3.296 0 .32.217.698.825.58C20.565 21.796 24 17.297 24 12c0-6.63-5.37-12-12-12z"
                        />
                    </svg>
                    Sign in with GitHub
                </Button>

                <!-- Optional Game Actions (only if logged in) -->
                <div v-if="$page.props.auth.user" class="w-full space-y-4 pt-6">
                    <form class="flex gap-2">
                        <Input
                            class="flex-1"
                            placeholder="Game code..."
                            type="text"
                        />
                        <Button type="submit">Join Game</Button>
                    </form>
                    <form>
                        <Button class="w-full" type="submit">
                            Create Game
                        </Button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</template>
