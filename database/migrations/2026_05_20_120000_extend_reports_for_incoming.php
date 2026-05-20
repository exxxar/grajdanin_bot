<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('phone', 32)->nullable()->after('from_user_id');
        });

        Schema::table('incoming_reports', function (Blueprint $table) {
            $table->json('problems')->nullable()->after('received_from');
            $table->json('solutions')->nullable()->after('problems');
            $table->json('difficulties')->nullable()->after('solutions');
            $table->json('audio_files')->nullable()->after('help_formats');
        });
    }

    public function down(): void
    {
        Schema::table('incoming_reports', function (Blueprint $table) {
            $table->dropColumn(['problems', 'solutions', 'difficulties', 'audio_files']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
