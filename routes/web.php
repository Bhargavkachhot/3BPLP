<?php 

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\GetController;
use App\Http\Controllers\Frontend\LoginController;  

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
  
// No Permission
Route::get('/403', function () {
    return view('errors.403');
})->name('frontend.NoPermission');

Route::get('/phpinfo', function () {
    phpinfo();
    exit;
});

Route::get('/', function () {
    return Redirect::route('admin.login');
});
Route::get('/admin', function () {
    return Redirect::route('admin.login');
});

// Not Found
Route::get('404', function () {
    return view('frontEnd.404');
})->name('NotFound');

Route::get('404-not-found', function () {
    return view('frontEnd.404');
})->name('frontend.not_found');

 

Route::Group(['prefix' => env('BACKEND_PATH')], function () {
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\LoginController::class, 'forgotpass']);
    Route::post('/forgot/user', [\App\Http\Controllers\Auth\LoginController::class, 'mainuserforgot']);

    Route::middleware(['preventBackHistory'])->group(function () {
        Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showMainuserLoginForm'])->name('admin.login');
        Route::post('/adminLogin', [\App\Http\Controllers\Auth\LoginController::class, 'adminLogin'])->name('adminLogin');
        Route::post('/main-user-logout', [\App\Http\Controllers\Auth\LoginController::class, 'logoutMainUser'])->name('main-user-logout');
    });
});

  


// Clear Cache
Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return redirect()->back()->with('doneMessage', __('backend.cashClearDone'));
})->name('cacheClear');

Route::get('/route-clear', function () {
    // Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('route:list');
    return redirect()->back()->with('doneMessage', 'Routes cleared');
})->name('routeClear');



Route::get('/verify-email/{id}', [GetController::class, 'VerifyEmail'])->name('verify.email');