<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('media_entries', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'media_entries_user_status_index');
            $table->index(['user_id', 'type'], 'media_entries_user_type_index');
            $table->index(['user_id', 'updated_at'], 'media_entries_user_updated_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('media_entries', function (Blueprint $table) {
            $table->dropIndex('media_entries_user_status_index');
            $table->dropIndex('media_entries_user_type_index');
            $table->dropIndex('media_entries_user_updated_at_index');
        });
    }
};
