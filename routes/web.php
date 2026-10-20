<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceAreaController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/gallery', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/gallery/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/service-areas', [ServiceAreaController::class, 'index'])->name('areas.index');
Route::get('/service-areas/{area:slug}', [ServiceAreaController::class, 'show'])->name('areas.show');

Route::middleware('throttle:inquiries')->group(function () {
    Route::post('/book-consultation', [InquiryController::class, 'consultation'])->name('inquiries.consultation');
    Route::post('/contact', [InquiryController::class, 'contact'])->name('inquiries.contact');
});
Route::get('/thank-you', [InquiryController::class, 'thanks'])->name('inquiries.thanks');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/ads.txt', [SeoController::class, 'adsTxt'])->name('ads-txt');

require __DIR__.'/admin.php';

// CMS pages (about-us, our-process, faqs, contact, legal…) — keep last.
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('pages.show');
