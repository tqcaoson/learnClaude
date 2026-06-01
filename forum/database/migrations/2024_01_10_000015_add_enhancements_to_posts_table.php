<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('board_id')->nullable()->after('user_id');
            $table->string('featured_image')->nullable()->after('content');
            $table->string('slug')->nullable()->unique()->after('title');
            $table->unsignedInteger('views_count')->default(0)->after('status');
            $table->boolean('is_locked')->default(false)->after('views_count');
            $table->timestamp('hidden_at')->nullable()->after('is_locked');

            $table->foreign('board_id')->references('id')->on('boards')->nullOnDelete();
            $table->index('slug');
            $table->index('board_id');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['board_id']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['board_id']);
            $table->dropColumn(['board_id', 'featured_image', 'slug', 'views_count', 'is_locked', 'hidden_at']);
        });
    }
};
