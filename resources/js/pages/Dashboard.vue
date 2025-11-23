<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const quote = ref<{
    greek: string;
    translation: string;
    attributed_to: string;
} | null>(null);

const loading = ref(false);
const canGetMore = ref(true);
const quotesRemainingToday = ref(8);
const limitReached = ref(false);
const timeUntilMidnight = ref<{ hours: number; minutes: number } | null>(null);
const error = ref<string | null>(null);

const fetchQuote = async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await axios.get('/quote/random');
        const data = response.data;

        if (data.quote) {
            quote.value = data.quote;
        }

        canGetMore.value = data.canGetMore ?? false;
        quotesRemainingToday.value = data.quotesRemainingToday ?? 0;
        limitReached.value = data.limitReached ?? false;
        timeUntilMidnight.value = data.timeUntilMidnight ?? null;
    } catch (err: any) {
        error.value = err.response?.data?.error || 'Failed to fetch quote';
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchQuote();
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <!-- Quote Card -->
            <div class="mx-auto w-full max-w-2xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Greek Wisdom</CardTitle>
                        <CardDescription>
                            Daily quote from ancient Greek philosophy
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Loading State -->
                        <div v-if="loading" class="space-y-4">
                            <Skeleton class="h-8 w-full" />
                            <Skeleton class="h-6 w-3/4" />
                            <Skeleton class="h-4 w-1/2" />
                        </div>

                        <!-- Quote Display -->
                        <div v-else-if="quote" class="space-y-4">
                            <div class="space-y-2">
                                <p class="text-2xl font-semibold text-foreground">
                                    {{ quote.greek }}
                                </p>
                                <p class="text-lg text-muted-foreground">
                                    {{ quote.translation }}
                                </p>
                                <p class="text-sm italic text-muted-foreground">
                                    — {{ quote.attributed_to }}
                                </p>
                            </div>
                        </div>

                        <!-- Error State -->
                        <Alert v-if="error" variant="destructive">
                            <AlertDescription>{{ error }}</AlertDescription>
                        </Alert>

                        <!-- Limit Reached Message -->
                        <Alert v-if="limitReached && timeUntilMidnight">
                            <AlertDescription>
                                Daily limit reached (8 quotes). Come back in
                                {{ timeUntilMidnight.hours }} hours and
                                {{ timeUntilMidnight.minutes }} minutes for more
                                quotes!
                            </AlertDescription>
                        </Alert>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between">
                            <Button
                                @click="fetchQuote"
                                :disabled="!canGetMore || loading"
                                class="w-full"
                            >
                                <span v-if="loading">Loading...</span>
                                <span v-else>New Quote</span>
                            </Button>
                        </div>

                        <!-- Remaining Quotes Counter -->
                        <p
                            v-if="!limitReached"
                            class="text-center text-sm text-muted-foreground"
                        >
                            {{ quotesRemainingToday }} quotes remaining today
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Placeholder content below -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                </div>
            </div>
        </div>
    </AppLayout>
</template>
