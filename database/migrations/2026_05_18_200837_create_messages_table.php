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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->tinyInteger('type')->default(\App\Enums\ChatTypeEnum::DEFAULT->value);
            // Привязка к проблеме/отчёту
            $table->foreignId('report_id')->nullable();
            $table->timestamps();
        });


        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Автор сообщения (пользователь или админ)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('chat_id')->nullable();


            $table->foreignId('report_id')->nullable();


            // Текст сообщения
            $table->text('text')->nullable();

            // Вложения — JSON массив ссылок
            $table->json('attachments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
