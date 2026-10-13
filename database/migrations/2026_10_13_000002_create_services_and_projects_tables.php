<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon', 40)->nullable();
            $table->string('short_description', 500);
            $table->string('hero_title');
            $table->text('hero_description');
            $table->foreignId('hero_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->longText('intro');
            $table->json('included')->nullable();   // [{title, text}]
            $table->json('styles')->nullable();     // [{title, text}]
            $table->json('benefits')->nullable();   // [{title, text}]
            $table->json('materials')->nullable();  // [{title, text}]
            $table->json('process')->nullable();    // optional override of global process [{title, text}]
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->string('canonical_url')->nullable();
            $table->foreignId('og_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_published', 'sort_order']);
        });

        Schema::create('service_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('heading');
            $table->longText('body');
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_media', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['service_id', 'media_id']);
        });

        Schema::create('service_related', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('related_service_id')->constrained('services')->cascadeOnDelete();
            $table->primary(['service_id', 'related_service_id']);
        });

        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('project_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location')->nullable();
            $table->string('summary', 500);
            $table->longText('overview')->nullable();
            $table->longText('requirements')->nullable();
            $table->longText('solution')->nullable();
            $table->longText('materials')->nullable();
            $table->foreignId('cover_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('before_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('after_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_published', 'sort_order']);
        });

        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_service', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'service_id']);
        });
    }

    public function down(): void
    {
        foreach (['project_service', 'project_images', 'projects', 'project_categories',
            'service_related', 'service_media', 'service_sections', 'services'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
