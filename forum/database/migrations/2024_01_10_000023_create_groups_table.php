<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('type', ['public', 'private'])->default('public');
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->unsignedInteger('members_count')->default(0);
            $table->unsignedInteger('posts_count')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
