<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quote_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at');
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'viewed_at']);
            $table->index(['user_id', 'quote_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_views');
    }
};
