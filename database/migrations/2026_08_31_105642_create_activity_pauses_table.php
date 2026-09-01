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
        Schema::create('activity_pauses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("activity_id")->constrained()->cascadeOnDelete();
            $table->uuid("client_id")->unique();
            $table->string("pause_at")->nullable();
            $table->string("resume_at")->nullable();
            $table->timestamps();
            $table->index(["client_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_pauses');
    }
};
