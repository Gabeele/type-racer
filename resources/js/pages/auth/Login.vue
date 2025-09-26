<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthBase
        description="Continue with GitHub to access your account"
        title="Log in to your account"
    >
        <Head title="Log in" />

        <div class="flex flex-col items-center gap-6">
            <Button
                as="a"
                class="w-full"
                data-test="login-github-button"
                href="/auth/github/redirect"
            >
                <!-- GitHub logo optional -->
                <svg
                    class="mr-2 h-5 w-5"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.387.6.113.82-.262.82-.582 0-.287-.01-1.046-.015-2.05-3.338.726-4.042-1.61-4.042-1.61-.546-1.385-1.334-1.754-1.334-1.754-1.09-.745.083-.73.083-.73 1.205.084 1.84 1.237 1.84 1.237 1.07 1.835 2.807 1.305 3.492.997.108-.776.418-1.305.762-1.605-2.665-.305-5.466-1.334-5.466-5.933 0-1.31.47-2.38 1.237-3.22-.123-.304-.536-1.527.117-3.176 0 0 1.01-.323 3.3 1.23a11.5 11.5 0 0 1 3-.404c1.02.005 2.045.138 3 .404 2.29-1.553 3.3-1.23 3.3-1.23.653 1.65.24 2.872.117 3.176.767.84 1.237 1.91 1.237 3.22 0 4.61-2.802 5.625-5.475 5.922.43.372.81 1.104.81 2.227 0 1.61-.015 2.905-.015 3.296 0 .32.217.698.825.58C20.565 21.796 24 17.297 24 12c0-6.63-5.37-12-12-12z"
                    />
                </svg>
                Sign in with GitHub
            </Button>
        </div>
    </AuthBase>
</template>
