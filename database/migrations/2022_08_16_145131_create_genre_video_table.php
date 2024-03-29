<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('genre_video', function (Blueprint $table) {
            $table->uuid('genre_id')->index();
            $table->uuid('video_id')->index();

            $table->foreign('genre_id')->references('id')->on('genres');
            $table->foreign('video_id')->references('id')->on('videos');
            $table->unique(['genre_id', 'video_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('genre_video');
    }
};
