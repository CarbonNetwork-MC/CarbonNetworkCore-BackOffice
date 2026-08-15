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
        Schema::create('players', function (Blueprint $table) {
            $table->uuid()->primary()->unique();
            $table->string('username');
            $table->unsignedBigInteger('rank_id');
            $table->integer('coins')->default(0);
            $table->enum('chat_channel', ['all', 'party', 'guild', 'staff'])->default('all');
            $table->unsignedBigInteger('selected_language')->nullable();
            $table->boolean('clear_inventory')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_logout_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('rank_id')->references('id')->on('ranks')->onDelete('cascade');
            $table->foreign('selected_language')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
