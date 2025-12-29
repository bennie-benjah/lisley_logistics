<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipment_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->string('location');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['shipment_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipment_updates');
    }
};