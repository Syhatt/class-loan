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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('booking_classes_id')->constrained();
            $table->foreignId('item_id')->constrained();
            $table->integer('qty');
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
            $table->string('hari_pengembalian')->nullable();
            $table->date('tanggal_pengembalian')->nullable();
            $table->time('jam_pengembalian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
