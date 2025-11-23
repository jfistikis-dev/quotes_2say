<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('greek')->unique(); // enforce uniqueness
            $table->string('translit')->nullable();
            $table->text('translation')->nullable();
            $table->string('attributed_to')->nullable();
            // extra fields requested
            $table->string('source_url')->nullable();
            $table->string('language_code', 8)->default('gr');
            $table->text('notes')->nullable();
            $table->tinyInteger('difficulty')->default(1); // e.g. 1..5
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}
