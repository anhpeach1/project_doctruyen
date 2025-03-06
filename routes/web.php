<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReadingHistoryController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\HashtagController;
use App\Http\Controllers\AdminStoryController;
use App\Http\Controllers\UserStoryController;
use App\Http\Controllers\AdminUserController;

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    if (Auth::user()->userType == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->userType == 'user') {
        return redirect()->route('user.stories.index');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// User routes
Route::middleware(['auth', 'userMiddleware'])->group(function () {
    Route::get('/user/index', [UserController::class, 'index'])->name('user.index');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/stories/dashboard', [UserStoryController::class, 'dashboard'])
            ->name('stories.dashboard');
        // Add reading history routes
        Route::get('/reading-histories', [ReadingHistoryController::class, 'index'])->name('reading-histories.index');
        Route::post('/reading-histories', [ReadingHistoryController::class, 'store'])->name('reading-histories.store');
        Route::resource('/stories', UserStoryController::class);
    });
});

// Admin routes
Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    // Dashboard
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Admin profile
    Route::get('admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    
    // User management routes - sửa thành các route riêng biệt thay vì dùng resource
    Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('admin/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('admin/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('admin/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Routes chuyên biệt phải đặt trước routes có biến
    Route::get('admin/stories/pending', [AdminStoryController::class, 'pendingStories'])->name('admin.stories.pending');
    Route::get('admin/stories/review/{id}', [AdminStoryController::class, 'reviewStory'])->name('admin.stories.review');
    Route::post('admin/stories/approve/{id}', [AdminStoryController::class, 'approveStory'])->name('admin.stories.approve');
    Route::post('admin/stories/reject/{id}', [AdminStoryController::class, 'rejectStory'])->name('admin.stories.reject');
    
    // Các route khác cho quản lý truyện
    Route::get('admin/stories', [AdminStoryController::class, 'index'])->name('admin.stories');
    Route::get('admin/stories/{id}', [AdminStoryController::class, 'show'])->name('admin.stories.show');
    Route::get('admin/stories/{id}/edit', [AdminStoryController::class, 'edit'])->name('admin.stories.edit');
    Route::put('admin/stories/{id}', [AdminStoryController::class, 'update'])->name('admin.stories.update');
    Route::delete('admin/stories/{id}', [AdminStoryController::class, 'destroy'])->name('admin.stories.destroy');
    
    // Reading histories management
    Route::get('admin/reading-histories', [AdminStoryController::class, 'readingHistories'])->name('admin.reading-histories');
    Route::delete('admin/reading-histories/{id}', [AdminStoryController::class, 'destroyReadingHistory'])->name('admin.reading-histories.destroy');
    
    // Hashtags management
    Route::get('admin/hashtags', [AdminController::class, 'hashtags'])->name('admin.hashtags');
    Route::post('admin/hashtags', [AdminController::class, 'storeHashtag'])->name('admin.hashtags.store');
    Route::put('admin/hashtags/{id}', [AdminController::class, 'updateHashtag'])->name('admin.hashtags.update');
    Route::delete('admin/hashtags/{id}', [AdminController::class, 'destroyHashtag'])->name('admin.hashtags.destroy');
});

// Public story routes - Chỉ định nghĩa một lần
Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
Route::get('/stories/{id}', [StoryController::class, 'show'])->name('stories.show');
Route::get('/stories/{id}/read', [StoryController::class, 'read'])->name('stories.read');
Route::post('/stories/{id}/track-view', [StoryController::class, 'trackView'])->name('stories.track-view');