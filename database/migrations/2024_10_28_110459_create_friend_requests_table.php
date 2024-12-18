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
        Schema::create('friend_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_type')->default('individual');
            $table->enum('role', ['ativist', 'admin'])->default('ativist');
            $table->boolean('verify')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friend_requests');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIfExists('account_type');
            $table->dropIfExists('verify');
        });
    }
};
