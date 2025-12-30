<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // If you have category_id but need category
        Schema::table('services', function (Blueprint $table) {
            // First, check what columns exist
            if (Schema::hasColumn('services', 'category_id')) {
                // Rename category_id to category
                $table->renameColumn('category_id', 'category');
                // Change from integer to string
                $table->string('category')->change();
            } else if (!Schema::hasColumn('services', 'category')) {
                // Add category column if it doesn't exist
                $table->string('category')->after('icon');
            }
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'category')) {
                $table->renameColumn('category', 'category_id');
                $table->integer('category_id')->change();
            }
        });
    }
};
