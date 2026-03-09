<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WoredaController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TenderController;
use App\Http\Controllers\Api\VacancyController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\LeadershipController;
use App\Http\Controllers\Api\EmergencyAlertController;
use App\Http\Controllers\Api\HeroSlideController;
use App\Http\Controllers\Api\AdminMessageController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\DirectoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TourismController;
use App\Http\Controllers\Api\StatsController;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// Admin Stats
Route::get('stats', [StatsController::class, 'index'])->middleware('auth:sanctum');

// Public Routes
Route::get('woredas', [WoredaController::class, 'index']);
Route::get('woredas/all', [WoredaController::class, 'index']); // Admin alias
Route::get('woredas/{idOrSlug}', [WoredaController::class, 'show']);

Route::get('news', [PostController::class, 'index']);
Route::get('news/{id}', [PostController::class, 'show']);

Route::get('tenders', [TenderController::class, 'index']);
Route::get('tenders/{id}', [TenderController::class, 'show']);

Route::get('vacancies', [VacancyController::class, 'index']);
Route::get('vacancies/{id}', [VacancyController::class, 'show']);

Route::get('documents', [DocumentController::class, 'index']);
Route::get('documents/{id}', [DocumentController::class, 'show']);

Route::get('leadership', [LeadershipController::class, 'index']);
Route::get('leadership/{id}', [LeadershipController::class, 'show']);

Route::get('alerts', [EmergencyAlertController::class, 'index']);
Route::get('alerts/all', [EmergencyAlertController::class, 'index']); // Admin alias
Route::get('alerts/{id}', [EmergencyAlertController::class, 'show']);

Route::get('hero', [HeroSlideController::class, 'index']); // renamed from hero-slides to hero
Route::get('hero/{id}', [HeroSlideController::class, 'show']);

Route::get('admin-message', [AdminMessageController::class, 'index']);
Route::get('admin-message/{id}', [AdminMessageController::class, 'show']);

Route::get('projects', [ProjectController::class, 'index']);
Route::get('projects/admin/all', [ProjectController::class, 'index']); // Admin alias
Route::get('projects/{id}', [ProjectController::class, 'show']);

Route::get('gallery', [GalleryController::class, 'index']);
Route::get('gallery/all', [GalleryController::class, 'index']); // Admin alias
Route::get('gallery/categories', [GalleryController::class, 'categories']);
Route::get('gallery/{id}', [GalleryController::class, 'show']);

Route::get('investments', [InvestmentController::class, 'index']);
Route::get('investments/{id}', [InvestmentController::class, 'show']);

Route::post('contact', [ContactController::class, 'store']);

Route::get('settings', [SettingsController::class, 'index']);

Route::get('directory', [DirectoryController::class, 'index']);
Route::get('directory/{id}', [DirectoryController::class, 'show']);

Route::get('tourism', [TourismController::class, 'index']);
Route::get('tourism/categories', [TourismController::class, 'categories']);
Route::get('tourism/{slug}', [TourismController::class, 'show']);

// Admin Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('news', [PostController::class, 'store']);
    Route::put('news/{id}', [PostController::class, 'update']);
    Route::delete('news/{id}', [PostController::class, 'destroy']);

    Route::post('woredas', [WoredaController::class, 'store']);
    Route::put('woredas/{id}', [WoredaController::class, 'update']);
    Route::delete('woredas/{id}', [WoredaController::class, 'destroy']);

    Route::post('tenders', [TenderController::class, 'store']);
    Route::put('tenders/{id}', [TenderController::class, 'update']);
    Route::delete('tenders/{id}', [TenderController::class, 'destroy']);

    Route::post('vacancies', [VacancyController::class, 'store']);
    Route::put('vacancies/{id}', [VacancyController::class, 'update']);
    Route::delete('vacancies/{id}', [VacancyController::class, 'destroy']);

    Route::post('documents', [DocumentController::class, 'store']);
    Route::post('documents/upload', [DocumentController::class, 'store']); // Alias for frontend
    Route::put('documents/{id}', [DocumentController::class, 'update']);
    Route::delete('documents/{id}', [DocumentController::class, 'destroy']);

    Route::post('leadership', [LeadershipController::class, 'store']);
    Route::put('leadership/{id}', [LeadershipController::class, 'update']);
    Route::delete('leadership/{id}', [LeadershipController::class, 'destroy']);

    Route::post('alerts', [EmergencyAlertController::class, 'store']);
    Route::put('alerts/{id}', [EmergencyAlertController::class, 'update']);
    Route::put('alerts/{id}/toggle', [EmergencyAlertController::class, 'update']); // Alias for toggle
    Route::delete('alerts/{id}', [EmergencyAlertController::class, 'destroy']);

    Route::post('hero', [HeroSlideController::class, 'store']);
    Route::put('hero/{id}', [HeroSlideController::class, 'update']);
    Route::delete('hero/{id}', [HeroSlideController::class, 'destroy']);

    Route::post('admin-message', [AdminMessageController::class, 'store']);
    Route::put('admin-message', [AdminMessageController::class, 'store']); // Some use cases might use POST/PUT interchangeably if only one message exists
    Route::put('admin-message/{id}', [AdminMessageController::class, 'update']);
    Route::delete('admin-message/{id}', [AdminMessageController::class, 'destroy']);

    Route::post('projects', [ProjectController::class, 'store']);
    Route::put('projects/{id}', [ProjectController::class, 'update']);
    Route::delete('projects/{id}', [ProjectController::class, 'destroy']);

    Route::post('gallery', [GalleryController::class, 'store']);
    Route::put('gallery/{id}', [GalleryController::class, 'update']);
    Route::delete('gallery/{id}', [GalleryController::class, 'destroy']);

    Route::post('investments', [InvestmentController::class, 'store']);
    Route::put('investments/{id}', [InvestmentController::class, 'update']);
    Route::delete('investments/{id}', [InvestmentController::class, 'destroy']);

    Route::post('directory', [DirectoryController::class, 'store']);
    Route::put('directory/{id}', [DirectoryController::class, 'update']);
    Route::delete('directory/{id}', [DirectoryController::class, 'destroy']);

    Route::get('contact', [ContactController::class, 'index']);
    Route::delete('contact/{id}', [ContactController::class, 'destroy']);

    Route::post('settings', [SettingsController::class, 'store']);

    Route::post('tourism', [TourismController::class, 'store']);
    Route::put('tourism/{id}', [TourismController::class, 'update']);
    Route::delete('tourism/{id}', [TourismController::class, 'destroy']);
});