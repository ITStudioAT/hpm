<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Spa\RouteController;
use App\Http\Controllers\Admin\SpaRoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\UserWithRoleController;



Route::middleware(['api'])->group(function () {

    /***** FONTSETS *****/
    // Dynamic CSS serving

    Route::get('css/fontset/{slug}.css', [\App\Http\Controllers\Homepage\FontsetController::class, 'serve']);
    Route::get('/css/colors/{slug}.css', [\App\Http\Controllers\Homepage\ColorsetController::class, 'css']);
});
// Globales Throttle
Route::middleware(['api', 'throttle:global', 'throttle:api'])->group(function () {
    Route::get('/fontsets', [\App\Http\Controllers\Homepage\FontsetController::class, 'list']);
    Route::get('/colorsets', [\App\Http\Controllers\Homepage\ColorsetController::class, 'list']);


    /***** OTHER ROUTES *****/
    Route::post('/routes/is_route_allowed',  [RouteController::class, 'isRouteAllowed']);

    /***** HOMEPAGE ROUTES *****/

    Route::get('/homepage/load_homepage',  [\App\Http\Controllers\Homepage\HomepageController::class, 'loadHomepage']);
    Route::get('/homepage/load_record',  [\App\Http\Controllers\Homepage\HomepageController::class, 'loadRecord']);


    /***** ADMIN ROUTES *****/

    Route::get('/admin/config',  [AdminController::class, 'config']);

    Route::post('/admin/login_step_1',  [AdminController::class, 'loginStep1']);
    Route::post('/admin/login_step_2',  [AdminController::class, 'loginStep2']);
    Route::post('/admin/login_step_3',  [AdminController::class, 'loginStep3']);

    Route::post('/admin/password_unknown_step_1',  [AdminController::class, 'passwordUnknownStep1']);
    Route::post('/admin/password_unknown_step_2',  [AdminController::class, 'passwordUnknownStep2']);
    Route::post('/admin/password_unknown_step_3',  [AdminController::class, 'passwordUnknownStep3']);
    Route::post('/admin/password_unknown_step_4',  [AdminController::class, 'passwordUnknownStep4']);

    Route::post('/admin/register_step_1',  [AdminController::class, 'registerStep1']);
    Route::post('/admin/register_step_2',  [AdminController::class, 'registerStep2']);
    Route::post('/admin/register_step_3',  [AdminController::class, 'registerStep3']);

    /* vom User ausgelöste APis zur E-Mail-Verifikation */
    Route::post('/admin/users/send_verification_email_initialized_from_user',  [UserController::class, 'sendVerificationEmailInitializedFromUser']);
    Route::post('/admin/users/email_verification',  [UserController::class, 'emailVerification']);



    /* SANCTUM - user */
    Route::middleware(['auth:sanctum', 'api-allowed:user,admin'])->group(function () {
        Route::put('/admin/users/update_profile/{user}',  [UserController::class, 'updateProfile']);
        Route::post('/admin/users/update_with_code',  [UserController::class, 'updateWithCode']);
        Route::post('/admin/execute_logout',  [AdminController::class, 'executeLogout']);
        Route::post('/admin/users/save_password',  [UserController::class, 'savePassword']);
        Route::post('/admin/users/save_password_with_code',  [UserController::class, 'savePasswordWithCode']);
    });

    /* SANCTUM - admin */
    Route::middleware(['auth:sanctum', 'api-allowed:admin'])->group(function () {
        Route::get('/admin/homepage/index',  [\App\Http\Controllers\Admin\HomepageController::class, 'loadHomepages']);
        Route::get('/admin/homepage/load_homepage',  [\App\Http\Controllers\Admin\HomepageController::class, 'loadHomepage']);
        Route::get('/admin/homepage/load_headers',  [\App\Http\Controllers\Admin\HomepageController::class, 'loadHeaders']);
        Route::get('/admin/homepage/load_record',  [\App\Http\Controllers\Admin\HomepageController::class, 'loadRecord']);
        Route::post('/admin/homepage/create',  [\App\Http\Controllers\Admin\HomepageController::class, 'createHomepage']);
        Route::post('/admin/homepage/delete',  [\App\Http\Controllers\Admin\HomepageController::class, 'deleteHomepage']);
        Route::post('/admin/homepage/save',  [\App\Http\Controllers\Admin\HomepageController::class, 'saveHomepage']);
        Route::post('/admin/homepage/save_record',  [\App\Http\Controllers\Admin\HomepageController::class, 'saveRecord']);
        Route::post('/admin/homepage/copy_record',  [\App\Http\Controllers\Admin\HomepageController::class, 'copyRecord']);
        Route::post('/admin/homepage/delete_record',  [\App\Http\Controllers\Admin\HomepageController::class, 'deleteRecord']);
        Route::post('/admin/homepage/rename_record',  [\App\Http\Controllers\Admin\HomepageController::class, 'renameRecord']);


        // navigation, menus
        Route::get('/admin/navigation/load_menu',  [NavigationController::class, 'loadMenu']);

        // users
        Route::apiResource('/admin/users', UserController::class);
        Route::post('/admin/users/destroy_multiple',  [UserController::class, 'destroyMultiple']);
        Route::post('/admin/users/send_verification_email',  [UserController::class, 'sendVerificationEmail']);
        Route::post('/admin/users/confirm',  [UserController::class, 'confirm']);
        Route::post('/admin/users/save_user_roles',  [UserController::class, 'saveUserRoles']);
        Route::post('/admin/users/save_2fa',  [UserController::class, 'save2Fa']);
        Route::post('/admin/users/save_2fa_with_code',  [UserController::class, 'save2FaWithCode']);


        // roles
        Route::apiResource('/admin/roles', SpaRoleController::class);
        Route::post('/admin/roles/destroy_multiple',  [SpaRoleController::class, 'destroyMultiple']);

        // users_with_roles
        Route::get('/admin/users_with_roles/roles',  [UserWithRoleController::class, 'roles']);
        Route::post('/admin/users_with_roles/roles',  [UserWithRoleController::class, 'saveUserRoles']);
        Route::apiResource('/admin/users_with_roles', UserWithRoleController::class);
    });
});
