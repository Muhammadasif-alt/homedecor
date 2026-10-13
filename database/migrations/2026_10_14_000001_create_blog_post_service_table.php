<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Related articles for a service page ("Further reading"), chosen in the admin.
 * Complements blog_posts.service_id, which links an article to its single main service.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_post_service', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['service_id', 'blog_post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_post_service');
    }
};
