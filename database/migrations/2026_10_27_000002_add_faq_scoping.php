<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('faqs')) {
            return;
        }

        if (!Schema::hasColumn('faqs', 'page_slug')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->string('page_slug')->nullable()->index();
            });
        }

        if (!Schema::hasColumn('faqs', 'game_id')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->foreignId('game_id')->nullable()->constrained('games')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('faqs')) {
            return;
        }

        if (Schema::hasColumn('faqs', 'game_id')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->dropForeign(['game_id']);
                $table->dropColumn('game_id');
            });
        }

        if (Schema::hasColumn('faqs', 'page_slug')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->dropIndex(['page_slug']);
                $table->dropColumn('page_slug');
            });
        }
    }
};
