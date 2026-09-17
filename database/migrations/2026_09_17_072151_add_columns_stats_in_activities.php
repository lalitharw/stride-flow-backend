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
        Schema::table('activities', function (Blueprint $table) {
            $table->double("distance")->nullable();
            $table->double("duration")->nullable();
            $table->double("average_speed")->nullable();
            $table->double("average_pace")->nullable();
            $table->double("elevation_gain")->nullable();
            $table->double("current_elevation")->nullable();
            $table->double("max_speed")->nullable();
            $table->text("map_image_url")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                "distance","duration","average_speed","average_pace","elevation_gain","current_elevation","max_speed","map_image_url"
            ]);
        });
    }
};
