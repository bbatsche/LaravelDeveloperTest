<?php

declare(strict_types=1);

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
        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->integer('post_id', unsigned: true)->unique();
            $table->integer('profile_id', unsigned: true);
            $table->string('title', 191)->index();
            $table->text('body');

            // Use the external ID that came from the API
            $table->foreign('profile_id')->references('profile_id')->on('profiles');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
