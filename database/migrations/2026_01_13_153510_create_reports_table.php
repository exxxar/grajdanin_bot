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
            $table->id();
            $table->smallInteger('type')->default(0);
            $table->smallInteger('priority')->default(0);
            $table->foreignId('from_user_id')->comment('гражданин')->nullable();
            $table->foreignId('to_user_id')->comment('официальное лицо');
            $table->foreignId('municipality_id');
            $table->string('received_at');
            $table->json('documents')->nullable();
            $table->timestamps();
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
