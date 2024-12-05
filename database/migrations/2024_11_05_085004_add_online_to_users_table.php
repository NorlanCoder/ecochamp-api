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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('online')->nullable();
            $table->string('ifu')->nullable();
            $table->string('rccm')->nullable();
            $table->string('publication_journal')->nullable();
            $table->string('address')->nullable();
        
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->json('images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIfExists('online');
            $table->dropIfExists('ifu');
            $table->dropIfExists('rccm');
            $table->dropIfExists('publication_journal');
            $table->dropIfExists('address');
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIfExists('images');
        });
    }
};
