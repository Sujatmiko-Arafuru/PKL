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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'peminjaman_baru', 'pengembalian', etc.
            $table->string('title');
            $table->text('message');
            $table->string('status')->default('unread'); // 'unread', 'read'
            $table->unsignedBigInteger('peminjaman_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // admin yang menerima notifikasi
            $table->json('data')->nullable(); // additional data
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->foreign('peminjaman_id')->references('id')->on('peminjamans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['status', 'created_at']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
