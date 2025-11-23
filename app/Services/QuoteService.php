<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    const DAILY_LIMIT = 8;

    /**
     * Get a random quote for the user with weighted probability
     */
    public function getRandomQuote(User $user): ?array
    {
        // Check daily limit
        $dailyCount = $this->getDailyQuoteCount($user);
        
        if ($dailyCount >= self::DAILY_LIMIT) {
            return [
                'quote' => null,
                'canGetMore' => false,
                'quotesRemainingToday' => 0,
                'limitReached' => true,
                'timeUntilMidnight' => $this->getTimeUntilMidnight(),
            ];
        }

        // Get all quotes
        $allQuotes = Quote::all();
        
        if ($allQuotes->isEmpty()) {
            return null;
        }

        // Get weighted random quote
        $quote = $this->getWeightedRandomQuote($allQuotes, $user);
        
        if (!$quote) {
            return null;
        }

        // Record the view
        $this->recordView($user, $quote);

        return [
            'quote' => [
                'id' => $quote->id,
                'greek' => $quote->greek,
                'translation' => $quote->translation,
                'attributed_to' => $quote->attributed_to,
            ],
            'canGetMore' => ($dailyCount + 1) < self::DAILY_LIMIT,
            'quotesRemainingToday' => self::DAILY_LIMIT - ($dailyCount + 1),
            'limitReached' => false,
        ];
    }

    /**
     * Get count of quotes viewed today
     */
    private function getDailyQuoteCount(User $user): int
    {
        $startOfDay = Carbon::today();
        
        return DB::table('quote_views')
            ->where('user_id', $user->id)
            ->where('viewed_at', '>=', $startOfDay)
            ->count();
    }

    /**
     * Get weighted random quote based on view history
     */
    private function getWeightedRandomQuote(Collection $allQuotes, User $user): ?Quote
    {
        $now = Carbon::now();
        $weights = [];

        foreach ($allQuotes as $quote) {
            // Get the last time this user viewed this quote
            $lastView = DB::table('quote_views')
                ->where('user_id', $user->id)
                ->where('quote_id', $quote->id)
                ->orderBy('viewed_at', 'desc')
                ->first();

            if (!$lastView) {
                // Never seen - highest weight
                $weight = 100;
            } else {
                $lastViewDate = Carbon::parse($lastView->viewed_at);
                $daysSince = $now->diffInDays($lastViewDate);

                // Apply weighted probability based on time since last view
                if ($daysSince >= 30) {
                    $weight = 80; // Seen 30+ days ago
                } elseif ($daysSince >= 7) {
                    $weight = 50; // Seen 7-30 days ago
                } elseif ($daysSince >= 1) {
                    $weight = 20; // Seen 1-7 days ago
                } else {
                    $weight = 5; // Seen today (very low probability)
                }
            }

            $weights[$quote->id] = $weight;
        }

        // Calculate total weight
        $totalWeight = array_sum($weights);
        
        if ($totalWeight === 0) {
            return null;
        }

        // Select random quote based on weights
        $random = mt_rand(1, $totalWeight);
        $currentWeight = 0;

        foreach ($allQuotes as $quote) {
            $currentWeight += $weights[$quote->id];
            if ($random <= $currentWeight) {
                return $quote;
            }
        }

        // Fallback to random quote
        return $allQuotes->random();
    }

    /**
     * Record a quote view
     */
    private function recordView(User $user, Quote $quote): void
    {
        DB::table('quote_views')->insert([
            'user_id' => $user->id,
            'quote_id' => $quote->id,
            'viewed_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Get time until midnight
     */
    private function getTimeUntilMidnight(): array
    {
        $now = Carbon::now();
        $midnight = Carbon::tomorrow();
        
        $diff = $now->diff($midnight);
        
        return [
            'hours' => $diff->h,
            'minutes' => $diff->i,
        ];
    }
}
