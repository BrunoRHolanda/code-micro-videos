<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('category_genre', function (Blueprint $table) {
            $table->uuid('category_id')->index();
            $table->uuid('genre_id')->index();

            $table->foreign('genre_id')->references('id')->on('genres');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unique(['genre_id', 'category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_genre');
    }
};
