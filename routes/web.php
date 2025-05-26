<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ChannelController as AdminChannelController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VideoController;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::middleware(['auth'])->group(function () {
    Route::resource('channels', ChannelController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('videos', VideoController::class);
    Route::resource('comments' , CommentController::class);
});

Route::get('/videos/{video}/processing', [VideoController::class, 'processing'])->name('videos.processing');
Route::get('/videos/{video}/status', [VideoController::class, 'status'])->name('videos.status');


Route::get('/notifications', [NotificationController::class, 'index'])->name('notification');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notification.markAllRead');
Route::get('/search' , [VideoController::class , 'search'])->name('search');


Route::prefix('admin')->middleware(['auth'])->middleware(['can:view-dashboard'])->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::resource('videos', AdminVideoController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('channels', AdminChannelController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class)->middleware(['can:view-users']);
});

Route::post('change' , [LanguageController::class , 'chang'])->name('lang.chang');
