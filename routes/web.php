<?php

use Domain\Admin\Controllers\Auth\AuthenticatedSessionController as AuthenticatedAdminSessionController;
use Domain\Customer\Controllers\Auth\AuthenticatedSessionController as AuthenticatedCustomerSessionController;
use Domain\Language\Models\Language;
use Domain\Shared\Helpers\PHPHelper;
use Domain\User\Controllers\LogoutController;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// dump(PHPHelper::leftCut('AdminAdmins', 'Admin'));
// dump(UserService::getGuardedUsers());exit;
// throw new \Exception('alma!!!');
// dump('alma');exit;

// try {
//     dump(\Domain\Finance\Enums\InvoiceType::asSelectArray()); exit;
// } catch (\Exception $e) {
//     dump($e);exit;
// } catch (\Error $e) {
//     dump($e);exit;
// }

Route::get('language/{locale}', function ($locale) {
    $selectedLang = Language::getSystemLanguages()->where('locale', $locale)->first();
    if ($selectedLang) {
        session()->put('locale', $locale);
    }

    return redirect()->back();
})->name('locale');

Route::middleware(['register.session.data', 'log.route'])->group(function () {

    Route::match(['get', 'post'], '/logout', [LogoutController::class, 'logout'])
        ->name('logout');

    Route::match(['get', 'post'], '/kilepes', [LogoutController::class, 'logout'])
        ->name('logout');

    Route::match(['get', 'post'], '/admin/logout', [AuthenticatedAdminSessionController::class, 'logout'])
        ->name('admin.logout');

    Route::match(['get', 'post'], '/admin/kilepes', [AuthenticatedAdminSessionController::class, 'logout'])
        ->name('admin.logout');

    Route::match(['get', 'post'], '/customer/logout', [AuthenticatedCustomerSessionController::class, 'logout'])
        ->name('customer.logout');

    Route::match(['get', 'post'], '/customer/kilepes', [AuthenticatedCustomerSessionController::class, 'logout'])
        ->name('customer.logout');

    require __DIR__.'/web_public.php';

    require __DIR__.'/web_admin.php';

    require __DIR__.'/web_customer.php';

    Route::middleware(['redirect.unrecognized.route'])->group(function () {
        Route::get('/{any}', function () {
            return redirect('/admin');
        })->where('any', '.*');
    });

});
