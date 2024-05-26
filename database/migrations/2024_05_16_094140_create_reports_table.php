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
        Schema::create('reports', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('report_file_id')->nullable();
            $table->unsignedBigInteger('report_key')->nullable();
            $table->unsignedBigInteger('key_parent_id')->nullable();
            $table->string('formatted_value')->nullable();
            $table->string('value')->nullable();
            $table->string('name')->nullable();
            $table->string('field_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
