<?php

namespace App\Http\Controllers;

use App\Services\QuoteService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    /**
     * Get a random quote for the authenticated user
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $result = $this->quoteService->getRandomQuote($user);

        if (!$result) {
            return response()->json(['error' => 'No quotes available'], 404);
        }

        return response()->json($result);
    }
}
