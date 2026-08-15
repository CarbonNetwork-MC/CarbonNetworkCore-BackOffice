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
        Schema::create('player_staff_settings', function (Blueprint $table) {
            $table->id();
            $table->char('player_uuid', 36);
            // $table->json('settings');
            $table->boolean('all_connections')->default(true);
            $table->boolean('staff_connections')->default(true);
            $table->boolean('all_server_switches')->default(true);
            $table->boolean('staff_server_switches')->default(true);
            $table->timestamps();

            // Foreign keys
            $table->foreign('player_uuid')->references('uuid')->on('players')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_staff_settings');
    }
};
