<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement('ALTER TABLE posts ADD FULLTEXT fulltext_posts_title_content (title, content)');
        } catch (\Exception $e) {
            // May fail on SQLite or if index already exists
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE posts DROP INDEX fulltext_posts_title_content');
        } catch (\Exception $e) {
            // May fail on SQLite or if index does not exist
        }
    }
};
