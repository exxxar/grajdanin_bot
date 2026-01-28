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
        Schema::create('incoming_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained("reports");
            $table->string('received_from')->nullable();
            $table->text('problem_description');
            $table->json('help_formats')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_reports');
    }
};
