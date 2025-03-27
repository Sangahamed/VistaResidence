<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;



Route::prefix('admin')->name('admin.')->group(function(){

    Route::middleware(['guest','prevent.back.history'])->group(function(){
        Route::view('/Connexion','back.admin.auth.login')->name('login');
        Route::post('/login_handler',[AdminController::class,'loginHandler'])->name('login_handler');
        Route::get('two-factor', [AdminController::class, 'TwoFactorForm'])->name('two-factor.show');
        Route::post('two-factor-verification', [AdminController::class, 'verifyTwoFactor'])->name('two-factor.verify');
        // Route::view('/forgot-password','back.pages.admin.auth.forgot-password')->name('forgot-password');
        // Route::post('/send-password-reset-link',[AdminController::class,'sendPasswordResetLink'])->name('send-password-reset-link');
        // Route::get('/password/reset/{token}',[AdminController::class,'resetPassword'])->name('reset-password');
        // Route::post('/reset-password-handler',[AdminController::class,'resetPasswordHandler'])->name('reset-password-handler');
    });

    Route::middleware(['auth:admin','prevent.back.history','twofactor.verified'])->group(function(){

        Route::view('/Home','back.admin.home')->name('home');
        Route::post('/logout_handler',[AdminController::class,'logoutHandler'])->name('logout_handler');
    //     Route::get('/Profile',[AdminController::class,'profileView'])->name('profile');
    //     Route::post('/Change-profile-picture',[AdminController::class,'changeProfilePicture'])->name('change-profile-picture');
    //     Route::view('/Reglages','back.pages.admin.settings')->name('settings');
    //     Route::view('/Promotion','back.pages.promotion')->name('promotion');
    //     Route::get('/Utilisateur', [AdminController::class, 'showUsers'])->name('users');
    //     Route::post('/delete-user', [AdminController::class,'deleteUser'])->name('delete-user');
    //     Route::get('/Locations', [AdminController::class, 'showLocation'])->name('locations');
    //     Route::post('/delete-location', [AdminController::class,'deleteLocation'])->name('delete-location');
    //     Route::get('/Ventes', [AdminController::class, 'showVendre'])->name('ventes');
    //     Route::post('/delete-vendre', [AdminController::class,'deleteVendre'])->name('delete-vendre');
    //     Route::post('/change-logo',[AdminController::class,'changeLogo'])->name('change-logo');
    //     Route::post('/change-favicon',[AdminController::class,'changeFavicon'])->name('change-favicon');

    //     //CATEGORIES AND SUB CATEGORIES MANAGEMENT
    //     Route::prefix('manage-categories')->name('manage-categories.')->group(function(){
    //        Route::controller(CategoriesController::class)->group(function(){
    //            Route::get('/Type&Marque','catSubcatList')->name('cats-subcats-list');
    //            Route::get('/Ajouter-Type','addCategory')->name('add-category');
    //            Route::post('/store-category','storeCategory')->name('store-category');
    //            Route::get('/Modifier-Type','editCategory')->name('edit-category');
    //            Route::post('/update-category','updateCategory')->name('update-category');
    //            Route::get('/Ajouter-marque','addSubCategory')->name('add-subcategory');
    //            Route::post('/store-subcategory','storeSubCategory')->name('store-subcategory');
    //            Route::get('/Modifier-Marque','editSubCategory')->name('edit-subcategory');
    //            Route::post('/update-subcategory','updateSubCategory')->name('update-subcategory');
    //        });
    //     });

    //      //CATEGORIES AND SUB CATEGORIES MANAGEMENT
    //     Route::prefix('manage-abonnement')->name('manage-abonnement.')->group(function(){
    //        Route::controller(AbonnementPlanController::class)->group(function(){
    //            Route::get('/plan-abonnement','index')->name('plan-abonnement');
    //            Route::get('/Ajouter-abonnement','create')->name('add-abonnement');
    //            Route::post('/store-abonnement','store')->name('store-abonnement');
    //            Route::get('/Modifier-abonnement','edit')->name('edit-abonnement');
    //            Route::post('/update-abonnement','update')->name('update-abonnement');
    //            Route::post('/delete-abonnement','destroy')->name('delete-abonnement');

    //         //    Route::get('/Ajouter-marque','addSubCategory')->name('add-subcategory');
    //         //    Route::post('/store-subcategory','storeSubCategory')->name('store-subcategory');
    //         //    Route::get('/Modifier-Marque','editSubCategory')->name('edit-subcategory');
    //         //    Route::post('/update-subcategory','updateSubCategory')->name('update-subcategory');
    //        });
    //     });


    });

});
