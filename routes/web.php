<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailChangeController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicInterviewUrlController;
use App\Http\Controllers\SortingController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\StudyInterviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

Route::middleware(['throttle:5,1'])->group(function () {
    Route::get('/password/set', [VerificationController::class, 'showresetpassword']);
    Route::get('/setpassword', [VerificationController::class, 'showresetpassword']);
    Route::post('/password/new', [VerificationController::class, 'newpassword']);
    Route::get('/password/reset-form', [ResetPasswordController::class, 'reset'])->name('password.reset.form');
});

Route::middleware(['throttle:10,5'])->group(function () {
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});

Route::get('/interview/done', [InterviewController::class, 'done']);

Route::middleware(['interview'])->group(function () {
    Route::get('/interviews/new', [InterviewController::class, 'create']);
    Route::post('/interviews', [InterviewController::class, 'store']);
});

Route::lingua('translations');

Route::middleware(['haspowers', 'auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::post('/createdummystudy/{user}', [StudyController::class, 'store']);
    Route::post('/deletestudiesbyuser/{user}', [StudyController::class, 'deleteallbyuser']);
    Route::get('/users', [AdminController::class, 'indexUsers']);
    Route::get('/studies', [AdminController::class, 'showStudies']);
    Route::get('/downloadbackup', [AdminController::class, 'downloadBackup']);
    Route::get('/downloadyesterdaybackup', [AdminController::class, 'downloadYesterdayBackup']);
    Route::get('/supervisor', [AdminController::class, 'supervisorindex']);
    Route::post('/users/supervisor', [UserController::class, 'store']);
    Route::get('/notifications', [NotificationController::class, 'create']);
    Route::post('/notify', [NotificationController::class, 'store']);
    Route::get('/newsletter', [AdminController::class, 'listForNewsletter']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/verifyNewEmail', [EmailChangeController::class, 'verify'])->name('verifyNewEmail');
    Route::post('/changeemail', [EmailChangeController::class, 'change'])->name('change');

    Route::get('/asuser/{user}', [HomeController::class, 'asuser']);
    Route::get('switch/{locale}', [HomeController::class, 'changeLanguage']);

    Route::get('/studies/new', [StudyController::class, 'create'])->name('studies.create');
    Route::post('/studies', [StudyController::class, 'store']);
    Route::get('/studies/{study}/duplicate', [StudyController::class, 'duplicate']);
    Route::get('/studies/{study}/edit', [StudyController::class, 'edit']);
    Route::patch('/studies/{study}', [StudyController::class, 'update']);
    Route::get('/studies/{study}', [StudyController::class, 'show']);
    Route::delete('/studies/{study}', [StudyController::class, 'destroy']);
    Route::get('/studies/users/{study}', [StudyController::class, 'getUsers']);
    Route::post('/studies/invite', [StudyController::class, 'inviteUser']);
    Route::post('/studies/invite/{user}', [StudyController::class, 'removeFromStudy']);

    Route::get('/interview/{interview}/show', [InterviewController::class, 'show']);
    Route::delete('/interview/{interview}', [InterviewController::class, 'destroy']);
    Route::post('/interview/publicurl/create', [PublicInterviewUrlController::class, 'store']);
    Route::delete('/interview/publicurl/delete', [PublicInterviewUrlController::class, 'destroy']);
    Route::get('/export/{interview}/interview/', [InterviewController::class, 'Export']);
    Route::get('/export/{study}/study/', [StudyInterviewController::class, 'exportall']);

    Route::get('/interview/{interview}/sorting/{sorting}/show', [SortingController::class, 'show']);
    // Route::get('/sortings/{interview}/show', [SortingController::class, 'show']);
    Route::get('/interview/{interview}/sorting/{sorting}/download', [SortingController::class, 'download']);

    Route::post('/getinputdata', [UserController::class, 'getinputdata']);
    Route::post('/checkemail', [UserController::class, 'checkemail']);

    Route::post('/users', [UserController::class, 'store']);

    Route::get('/users', [UserController::class, 'overview']);
    Route::get('/user/{user}', [UserController::class, 'edit']);
    Route::patch('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/users/notifications', [NotificationController::class, 'show']);
    Route::post('/users/subscribe', [NotificationController::class, 'addToNewsletter']);
    Route::post('/users/notifications/update', [NotificationController::class, 'update']);

    Route::post('/users/{user}/{study}', [UserController::class, 'detachfromstudy']);
    Route::post('/users/{user}/canedit/{study}', [UserController::class, 'canEditStudy']);
    Route::patch('/userscanedit/{user}', [UserController::class, 'cancreatestudies']);
    Route::patch('/usersconfirm/{user}/', [UserController::class, 'sendresetpassword']);
});
