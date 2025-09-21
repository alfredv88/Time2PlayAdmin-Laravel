<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;


Route::get('login', [AuthController::class, 'login']);
// Route::post('login', [AuthController::class, 'adminLogin']);
Route::post('/login', [AuthController::class, 'adminLogin'])->name('admin.login');
Route::get('forgotPassword', [AuthController::class, 'forgotPassword']);
Route::post('verifyEmail', [AuthController::class, 'verifyEmail']);
Route::get('resendOtp', [AuthController::class, 'resendOtp']);
Route::get('otp', [AuthController::class, 'otp']);
Route::post('verify', [AuthController::class, 'verify']);
Route::get('newPassword', [AuthController::class, 'newPassword']);
Route::post('newPassword', [AuthController::class, 'changePassword']);
Route::post('logout', [AuthController::class, 'logout']);


Route::post('/session/store-otp', function (Request $r) {
    $r->validate([
        'email'   => 'required|email',
        'otp'     => 'required|string',
        'docId'   => 'required|string',
    ]);
    session([
        'reset_email'  => $r->email,
        'otp'          => $r->otp,
        'admin_doc_id' => $r->docId,
    ]);
    return response()->json(['ok' => true]);
})->name('session.store.otp');

Route::post('/session/clear-reset', function () {
    session()->forget(['reset_email', 'otp', 'admin_doc_id']);
    return response()->json(['ok' => true]);
})->name('session.clear.reset');


Route::middleware(['guard'])->group(function () {
    Route::resource('/', HomeController::class);

    // Users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/data', [UsersController::class, 'data'])->name('users.data'); // ✅ DataTables JSON
    Route::post('/users/{id}/toggle', [UsersController::class, 'toggle'])->name('users.toggle');
    Route::get('/users/active', [UsersController::class, 'activeUsers']);
    Route::get('/users/blocked', [UsersController::class, 'blockedUsers']);
    Route::get('/toggle-user-status/{userId}/{newStatus}', [UsersController::class, 'toggleStatus']);

    // ✅ Events
    Route::get('/events', [UsersController::class, 'events'])->name('events.index');
    Route::get('/events/data', [UsersController::class, 'eventsData'])->name('events.data');      // DataTables feed
    Route::get('/events/stats', [UsersController::class, 'eventsStats'])->name('events.stats');   // Counters
    Route::post('/events/{id}/status', [UsersController::class, 'updateEventStatus'])->name('events.updateStatus');
    // ✅ NEW: View a single event by document id
    Route::get('/events/{id}', [UsersController::class, 'viewEvent'])->name('events.show');

    // ✅ Legacy compatibility for old /view-event links:
    // /view-event/{id} -> redirect to /events/{id}; /view-event -> back to list
    Route::get('/view-event/{id?}', function ($id = null) {
        if (!$id) {
            return redirect()->route('events.index')->with('error', 'No event selected.');
        }
        return redirect()->route('events.show', $id);
    })->name('events.legacy');

    // Center management
    Route::get('/center-request', [UsersController::class, 'centerRequest']);
    // routes/web.php
    Route::get('/center-request-details/{id}', [UsersController::class, 'centerRequestDetails'])
        ->name('center.request.details');

    // Sports management
    Route::get('/add-sport', [UsersController::class, 'addSport']);
    Route::get('/sports-management', [UsersController::class, 'sportsManagement']);
    Route::get('/edit-sport/{id}', [UsersController::class, 'editSport'])->name('sports.edit');
    Route::post('/upload-sport-icon', [UsersController::class, 'upload'])
        ->name('sports.icon.upload');

    // Subscription control
    Route::get('/subscription-control', [UsersController::class, 'subscriptionControl'])->name('subscription.control');
    // Subscription Control main page (your blade below)
    // Route::get('/subscription-control', [SubscriptionController::class, 'index'])->name('subscription.control');

    // Edit plan (free | pro)
    Route::get('/subscription-plan/{type}/edit', [UsersController::class, 'editPlan'])->name('subscription.plan.edit');

    // Reuse your existing upload route for QR code too
    Route::post('/upload-sport-icon', [UsersController::class, 'upload'])->name('sports.icon.upload');

    Route::get('/toggle-user-status/{userId}/{newStatus}', [App\Http\Controllers\UsersController::class, 'toggleStatus']);
    Route::get('/users/active', [UsersController::class, 'activeUsers']);
    Route::post('update-password', [AuthController::class, 'updateAdminPassword'])->name('update.password');
    Route::get('/users/blocked', [UsersController::class, 'blockedUsers']);
    Route::post('/store-noti', [App\Http\Controllers\UsersController::class, 'store']);
    Route::get('/notifications', [UsersController::class, 'notifications']);
    Route::post('update-doc/{id}', [AuthController::class, 'updatePolicyDocument']);
    Route::post('update-privacy-policy', [AuthController::class, 'updatePrivacyPolicy'])->name('update.privacy');
    Route::delete('/noti/delete/{id}', [App\Http\Controllers\UsersController::class, 'delete']);
    Route::get('/noti/add', [UsersController::class, 'addNoti']);


    // web.php
    Route::get('/versions', [UsersController::class, 'versions'])->name('versions');


    Route::get('version/add', [UsersController::class, 'addVersion']);
    Route::post('version/update-status', [UsersController::class, 'updateVersionStatus']);

    Route::get('/change-password', [UsersController::class, 'changePassword']);
    Route::get('/policies',   [UsersController::class, 'policyDoc'])->name('policies');
    Route::get('/add-doc',    [UsersController::class, 'addDoc'])->name('policies.add');
    Route::get('/edit-doc/{id}', [UsersController::class, 'editDoc'])->name('policies.edit');

    // PDF upload to public/ and return absolute URL
    Route::post('/policies/pdf-upload', [UsersController::class, 'uploadPolicyPdf'])->name('policies.pdf.upload');

    Route::get('/versions', [UsersController::class, 'versions']);

    Route::get('help-requests', [UsersController::class, 'helpRequestsView']);
    Route::post('help-requests/reply/{id}', [UsersController::class, 'reply'])->name('help.requests.reply');
});



// Route::get('login', [AuthController::class, 'login']);
// // Route::post('login', [AuthController::class, 'adminLogin']);
// Route::post('/login', [AuthController::class, 'adminLogin'])->name('admin.login');
// Route::get('forgotPassword', [AuthController::class, 'forgotPassword']);
// Route::post('verifyEmail', [AuthController::class, 'verifyEmail']);
// Route::get('resendOtp', [AuthController::class, 'resendOtp']);
// Route::get('otp', [AuthController::class, 'otp']);
// Route::post('verify', [AuthController::class, 'verify']);
// Route::get('newPassword', [AuthController::class, 'newPassword']);
// Route::post('newPassword', [AuthController::class, 'changePassword']);
// Route::post('logout', [AuthController::class, 'logout']);

// Route::middleware(['guard'])->group(function () {
//     Route::resource('/', HomeController::class);
//     // Route::get('/users', [UsersController::class, 'users']);
//     Route::get('/users', [UsersController::class, 'index'])->name('users.index');

//     // Toggle status (block/unblock). POST keeps it simple (no JS required).
//     Route::post('/users/{id}/toggle', [UsersController::class, 'toggle'])->name('users.toggle');
//     // Events Requests
//     Route::get('/events', [UsersController::class, 'events']);
//     Route::get('/view-event', [UsersController::class, 'viewEvent']);

//     // center management
//     Route::get('/center-request', [UsersController::class, 'centerRequest']);
//     Route::get('/center-request-details', [UsersController::class, 'centerRequestDetails']);

//     // sports-management details
//     Route::get('/add-sport', [UsersController::class, 'addSport']);
//     Route::get('/sports-management', [UsersController::class, 'sportsManagement']);

//     // subscription control
//     Route::get('/subscription-control', [UsersController::class, 'subscriptionControl']);

//     Route::get('/toggle-user-status/{userId}/{newStatus}', [App\Http\Controllers\UsersController::class, 'toggleStatus']);
//     Route::get('/users/active', [UsersController::class, 'activeUsers']);
//     Route::post('update-password', [AuthController::class, 'updateAdminPassword'])->name('update.password');
//     Route::get('/users/blocked', [UsersController::class, 'blockedUsers']);
//     Route::post('/store-noti', [App\Http\Controllers\UsersController::class, 'store']);
//     Route::get('/notifications', [UsersController::class, 'notifications']);
//     Route::get('edit-doc/{id}', [AuthController::class, 'editPolicyDocument']);
//     Route::post('update-doc/{id}', [AuthController::class, 'updatePolicyDocument']);
//     Route::post('update-privacy-policy', [AuthController::class, 'updatePrivacyPolicy'])->name('update.privacy');
//     Route::delete('/noti/delete/{id}', [App\Http\Controllers\UsersController::class, 'delete']);
//     Route::get('/noti/add', [UsersController::class, 'addNoti']);
//     Route::get('/version/add', [UsersController::class, 'addVersion']);
//     Route::post('store-android-version', [UsersController::class, 'storeAndroidVersion']);
//     Route::post('version/update-status', [UsersController::class, 'updateVersionStatus']);
//     Route::get('/change-password', [UsersController::class, 'changePassword']);
//     Route::get('/policies', [UsersController::class, 'policyDoc']);
//     Route::get('/add-doc', [UsersController::class, 'addDoc']);
//     Route::get('/versions', [UsersController::class, 'versions']);
//     Route::get('help-requests', [UsersController::class, 'helpRequestsView']);
//     Route::post('help-requests/reply/{id}', [UsersController::class, 'reply'])->name('help.requests.reply');
// });
