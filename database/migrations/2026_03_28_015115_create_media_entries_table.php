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
        Schema::create('media_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Identificadores
            $table->string('title');
            $table->string('original_title')->nullable();
            $table->string('type'); // enum('type', ['anime', 'manga', 'manhwa', 'manhua', 'novel']);
            $table->string('cover_url')->nullable();

            // Referencias externas
            $table->unsignedBigInteger('mal_id')->nullable()->index();

            // Estado y progreso
            $table->enum('status', ['watching', 'rewatching', 'reading', 'completed', 'on_hold', 'dropped', 'plan_to_watch'])->default('plan_to_watch');

            // Progreso
            $table->unsignedSmallInteger('current_episode')->nullable(); // anime
            $table->unsignedSmallInteger('total_episodes')->nullable();  // anime
            $table->unsignedSmallInteger('current_chapter')->nullable(); // manga/manhwa/manhua/novel
            $table->unsignedSmallInteger('total_chapters')->nullable();
            $table->unsignedSmallInteger('current_volume')->nullable();  // manga/manhwa/manhua/novel
            $table->unsignedSmallInteger('total_volumes')->nullable();

            // Valoración
            $table->unsignedTinyInteger('rating')->nullable(); // 1-5

            // Notas
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->unique(['user_id', 'mal_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_entries');
    }
};
