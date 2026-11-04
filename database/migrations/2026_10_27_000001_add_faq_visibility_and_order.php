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

        if (!Schema::hasColumn('faqs', 'is_active')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }

        if (!Schema::hasColumn('faqs', 'sort_order')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('faqs')) {
            return;
        }

        $columns = [];
        foreach (['is_active', 'sort_order'] as $column) {
            if (Schema::hasColumn('faqs', $column)) {
                $columns[] = $column;
            }
        }

        if ($columns !== []) {
            Schema::table('faqs', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
