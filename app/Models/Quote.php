<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'greek',
        'translit',
        'translation',
        'attributed_to',
        'source_url',
        'language_code',
        'notes',
        'difficulty',
    ];

    /**
     * Users who have viewed this quote
     */
    public function viewers()
    {
        return $this->belongsToMany(User::class, 'quote_views')
            ->withTimestamps()
            ->withPivot('viewed_at');
    }

    /**
     * Check if a user has viewed this quote
     */
    public function hasBeenViewedBy(User $user): bool
    {
        return $this->viewers()->where('user_id', $user->id)->exists();
    }
}
