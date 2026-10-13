<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description', 500)->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500);
            $table->longText('body');
            $table->foreignId('featured_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete(); // CTA / internal link
            $table->string('status', 20)->default('draft'); // draft | published (scheduled = published with future date)
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->string('canonical_url')->nullable();
            $table->foreignId('og_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->boolean('robots_index')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'published_at']);
        });

        Schema::create('blog_post_category', function (Blueprint $table) {
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['blog_post_id', 'blog_category_id']);
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->text('content');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('photo_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->boolean('is_approved')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // One FAQ table for general and service-specific questions (service_id nullable).
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->foreignId('service_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('category', 40)->default('general')->index();
            $table->boolean('show_on_home')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Contact messages and consultation requests share one lead table (type column).
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->index();   // contact | consultation
            $table->string('status', 30)->default('new')->index();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 40)->nullable();
            $table->string('preferred_contact', 20)->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('property_type', 40)->nullable();
            $table->string('location')->nullable();
            $table->string('budget', 40)->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->timestamp('consent_at')->nullable();
            $table->string('source_url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inquiry_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['inquiry_notes', 'inquiries', 'faqs', 'testimonials', 'blog_post_category', 'blog_posts', 'blog_categories'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
