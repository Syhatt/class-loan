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
        Schema::create('booking_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('classmodel_id')->constrained();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('organization');
            $table->string('activity_name');
            $table->string('full_name');
            $table->string('nim');
            $table->string('semester');
            $table->string('prodi');
            $table->string('telp');
            $table->string('no_letter');
            $table->date('date_letter');
            // $table->string('signature');
            $table->string('apply_letter');
            $table->string('activity_proposal');
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_classes');
    }
};
