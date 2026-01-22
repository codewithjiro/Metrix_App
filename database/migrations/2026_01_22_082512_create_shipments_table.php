<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to sender
            $table->string('sender_name');
            $table->string('sender_address');
            $table->string('receiver_name');
            $table->string('receiver_address');
            $table->string('package_type'); // e.g., Box, Document
            $table->enum('status', ['Pending', 'In Transit', 'Delivered', 'Cancelled'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};