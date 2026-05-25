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
        Schema::table('media_entries', function (Blueprint $table) {
            $table->string('nukan_slug')->nullable()->after('mal_id')->index();
            $table->unique(['user_id', 'nukan_slug'], 'media_entries_user_id_nukan_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('media_entries', function (Blueprint $table) {
            $table->dropUnique('media_entries_user_id_nukan_slug_unique');
            $table->dropColumn('nukan_slug');
        });
    }
};
