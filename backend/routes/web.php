<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\WoredaController;
use App\Http\Controllers\Web\LocaleController;
use App\Http\Controllers\Web\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Web\Admin\DashboardController;

// ─── Locale Switcher ──────────────────────────────────────────────────────────
Route::get('/locale/{locale}', [LocaleController::class, 'setLocale'])->name('locale.set');

// ─── Public Pages ─────────────────────────────────────────────────────────────
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/news', [PageController::class, 'news'])->name('news.index');
Route::get('/news/{id}', [PageController::class, 'newsShow'])->name('news.show');
Route::get('/documents', [PageController::class, 'documents'])->name('documents.index');
Route::get('/documents/{id}/read', [PageController::class, 'documentRead'])->name('documents.read');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery.index');
Route::get('/about', [PageController::class, 'about'])->name('about.index');
Route::get('/leadership', [PageController::class, 'leadership'])->name('leadership.index');
Route::get('/tenders', [PageController::class, 'tenders'])->name('tenders.index');
Route::get('/tenders/{id}', [PageController::class, 'tenderShow'])->name('tenders.show');
Route::get('/vacancies', [PageController::class, 'vacancies'])->name('vacancies.index');
Route::get('/vacancies/{id}', [PageController::class, 'vacancyShow'])->name('vacancies.show');
Route::get('/projects', [PageController::class, 'projects'])->name('projects.index');
Route::get('/projects/{id}', [PageController::class, 'projectsShow'])->name('projects.show');
Route::get('/investment', [PageController::class, 'investment'])->name('investment.index');
Route::get('/directory', [PageController::class, 'directory'])->name('directory.index');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');
Route::get('/contact', [PageController::class, 'contact'])->name('contact.index');
Route::get('/tourism', [PageController::class, 'tourismIndex'])->name('tourism.index');
Route::get('/tourism/{slug}', [PageController::class, 'tourismShow'])->name('tourism.show');

// ─── Woreda Portal ────────────────────────────────────────────────────────────
Route::prefix('/woreda/{slug}')->name('woreda.')->group(function () {
    Route::get('/', [WoredaController::class, 'show'])->name('show');
    Route::get('/about', [WoredaController::class, 'about'])->name('about');
    Route::get('/gallery', [WoredaController::class, 'gallery'])->name('gallery');
    Route::get('/services', [WoredaController::class, 'services'])->name('services');
    Route::get('/contact', [WoredaController::class, 'contact'])->name('contact');
});

// ─── Admin Auth ───────────────────────────────────────────────────────────────
Route::prefix('/admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Livewire-backed admin pages
        Route::get('/news', fn() => view('admin.news.index'))->name('news.index');
        Route::get('/woredas', fn() => view('admin.woredas.index'))->name('woredas.index');
        Route::get('/service-sectors', fn() => view('admin.service-sectors.index'))->name('service-sectors.index');
        Route::get('/gallery', fn() => view('admin.gallery.index'))->name('gallery.index');
        Route::get('/hero', fn() => view('admin.hero.index'))->name('hero.index');
        Route::get('/tenders', fn() => view('admin.tenders.index'))->name('tenders.index');
        Route::get('/vacancies', fn() => view('admin.vacancies.index'))->name('vacancies.index');
        Route::get('/documents', fn() => view('admin.documents.index'))->name('documents.index');
        Route::get('/projects', fn() => view('admin.projects.index'))->name('projects.index');
        Route::get('/leadership', fn() => view('admin.leadership.index'))->name('leadership.index');
        Route::get('/alerts', fn() => view('admin.alerts.index'))->name('alerts.index');
        Route::get('/investments', fn() => view('admin.investments.index'))->name('investments.index');
        Route::get('/directory', fn() => view('admin.directory.index'))->name('directory.index');
        Route::get('/contact', fn() => view('admin.contact.index'))->name('contact.index');
        Route::get('/settings', fn() => view('admin.settings.index'))->name('settings.index');
        Route::get('/message', fn() => view('admin.message.index'))->name('message.index');
        Route::get('/users', fn() => view('admin.users.index'))->name('users.index');
        Route::get('/tourism', fn() => view('admin.tourism.index'))->name('tourism.index');
    });
});
