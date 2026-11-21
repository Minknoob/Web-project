<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            if (! Schema::hasColumn('notes', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false);
            }
            if (! Schema::hasColumn('notes', 'pinned_at')) {
                $table->timestamp('pinned_at')->nullable();
            }
            if (! Schema::hasColumn('notes', 'password')) {
                $table->string('password')->nullable();
            }
        });

        if (Schema::hasColumn('notes', 'pinned')) {
            DB::table('notes')->where('pinned', true)->update([
                'is_pinned' => true,
                'pinned_at' => now(),
            ]);

            Schema::table('notes', function (Blueprint $table) {
                $table->dropColumn('pinned');
            });
        }

        Schema::table('notes', function (Blueprint $table) {
            foreach (['labels', 'shared', 'locked', 'image', 'image_style', 'image_alt'] as $column) {
                if (Schema::hasColumn('notes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->boolean('pinned')->default(false);
        });

        DB::table('notes')->where('is_pinned', true)->update(['pinned' => true]);

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['is_pinned', 'pinned_at', 'password']);
        });
    }
};
