<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('video_file')->nullable();
        });
    }

    public function down()
    {
        Schema::dropColumns('videos', ['video_file']);
    }
};
