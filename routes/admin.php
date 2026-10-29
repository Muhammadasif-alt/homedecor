<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogImportController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\InquiryNoteController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NavigationItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\ServiceAreaController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin dashboard / CMS
|--------------------------------------------------------------------------
| Self-contained: required from routes/web.php. Middleware are referenced by
| class name so no aliases need registering.
*/

Route::middleware('web')->prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');

    Route::middleware([AdminAuthenticate::class, EnsureUserIsActive::class])->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        // Pages + their ordered sections
        Route::resource('pages', PageController::class)->except('show');
        Route::scopeBindings()->prefix('pages/{page}/sections')->name('pages.sections.')
            ->controller(PageSectionController::class)->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('{section}/edit', 'edit')->name('edit');
                Route::put('{section}', 'update')->name('update');
                Route::delete('{section}', 'destroy')->name('destroy');
                Route::patch('{section}/move', 'move')->name('move');
                Route::patch('{section}/toggle', 'toggle')->name('toggle');
            });

        Route::resource('services', ServiceController::class)->except('show');
        Route::resource('projects', ProjectController::class)->except('show');
        Route::resource('project-categories', ProjectCategoryController::class)
            ->except('show')->parameters(['project-categories' => 'projectCategory']);

        // Media library (+ JSON feed for the picker)
        Route::get('media/library', [MediaController::class, 'library'])->name('media.library');
        Route::post('media/import-url', [MediaController::class, 'importUrl'])->name('media.import-url');
        Route::post('media/{media}/replace', [MediaController::class, 'replace'])->name('media.replace');
        Route::resource('media', MediaController::class)
            ->except(['create', 'show'])->parameters(['media' => 'media']);

        // Paste a whole article (Markdown from a brief) and publish it with photos
        Route::get('blog-posts/import', [BlogImportController::class, 'create'])->name('blog-posts.import');
        Route::post('blog-posts/import/preview', [BlogImportController::class, 'preview'])->name('blog-posts.import.preview');
        Route::post('blog-posts/import', [BlogImportController::class, 'store'])->name('blog-posts.import.store');

        Route::resource('blog-posts', BlogPostController::class)
            ->except('show')->parameters(['blog-posts' => 'blogPost']);
        Route::resource('blog-categories', BlogCategoryController::class)
            ->except('show')->parameters(['blog-categories' => 'blogCategory']);

        Route::resource('testimonials', TestimonialController::class)->except('show');
        Route::resource('faqs', FaqController::class)->except('show');

        // Leads
        Route::resource('inquiries', InquiryController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::get('inquiries/{inquiry}/attachments/{index}', [InquiryController::class, 'attachment'])
            ->whereNumber('index')->name('inquiries.attachments');
        Route::post('inquiries/{inquiry}/notes', [InquiryNoteController::class, 'store'])->name('inquiries.notes.store');
        Route::delete('inquiries/{inquiry}/notes/{note}', [InquiryNoteController::class, 'destroy'])
            ->scopeBindings()->name('inquiries.notes.destroy');

        Route::resource('navigation', NavigationItemController::class)
            ->except('show')->parameters(['navigation' => 'navigationItem']);
        Route::patch('navigation/{navigationItem}/move', [NavigationItemController::class, 'move'])->name('navigation.move');

        Route::resource('service-areas', ServiceAreaController::class)
            ->except('show')->parameters(['service-areas' => 'serviceArea']);

        // Administrators only
        Route::middleware(EnsureUserIsAdmin::class)->group(function () {
            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
            Route::resource('redirects', RedirectController::class)->except('show');
            Route::resource('users', UserController::class)->except('show');
        });
    });
});
