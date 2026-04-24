<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('media_entries')->whereNotNull('rating')->update([
            'rating' => DB::raw('CAST((rating + 1) / 2 AS INTEGER)'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // To reverse, we multiply by 2 (approximate)
        DB::table('media_entries')->whereNotNull('rating')->update([
            'rating' => DB::raw('rating * 2'),
        ]);
    }
};
