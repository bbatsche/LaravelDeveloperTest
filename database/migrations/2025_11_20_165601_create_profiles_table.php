<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create `profiles` table for `/users` API endpoint.
 *
 * Didn't want to risk overwriting the existing `users` table, although I'm not
 * likely to use it for this project.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table): void {
            $table->id();
            // Could probably be considered the primary key, but safer to let MySQL handle it
            $table->integer('profile_id', unsigned: true)->unique();
            $table->string('name', 191);
            $table->string('username', 191)->unique();
            $table->string('email', 191)->index();
            // Sample data appears to be North American numbers (although with some weird inconsistencies)
            $table->char('phone', 10);
            $table->string('extension', 8)->nullable();
            $table->string('website');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
