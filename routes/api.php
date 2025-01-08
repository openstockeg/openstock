<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ContactUsMessage\ContactUsMessageController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\SocialMediaLink\SocialMediaLinkController;
use App\Http\Controllers\StaticPage\StaticPageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-forgot-password', [AuthController::class, 'verifyForgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

});
Route::group([],function () {
    // Static Page
    Route::get('/static-pages', [StaticPageController::class, 'index']);
    Route::get('/static-pages/{slug}', [StaticPageController::class, 'show']);

    // Social Media Link
    Route::get('/social-media', [SocialMediaLinkController::class, 'index']);
    Route::get('/social-media/{slug}', [SocialMediaLinkController::class, 'show']);

    // Contact Us
    Route::post('/contact-us', [ContactUsMessageController::class, 'store']);
});

Route::group([
    'middleware' => 'auth:sanctum',
],function () {
    Route::post('/complete', [AuthController::class, 'complete']);
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/password', [ProfileController::class, 'updatePassword']);
});
