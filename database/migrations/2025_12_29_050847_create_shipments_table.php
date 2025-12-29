<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('sender_name');
            $table->string('sender_email');
            $table->string('receiver_name');
            $table->string('receiver_email');
            $table->text('description');
            $table->decimal('weight', 8, 2)->nullable();
            $table->enum('status', ['pending', 'processing', 'in_transit', 'out_for_delivery', 'delivered', 'delayed'])->default('pending');
            $table->string('current_location');
            $table->dateTime('estimated_delivery')->nullable();
            $table->dateTime('actual_delivery')->nullable();
            $table->timestamps();
            
            $table->index('tracking_number');
            $table->index('status');
            $table->index('estimated_delivery');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipments');
    }
};