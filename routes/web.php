<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProprietaireController;
use App\Http\Controllers\User\AccountTypeController;


Route::get('/', function () {
    return view('welcome');
});



Route::view('/detail','front\pages\detail');
Route::view('/index','front\pages\index');


Route::view('/proprietes','back\pages\propriete');
// Route::view('/addproprietes','back\pages\addpropriete');
Route::view('/paramettre','back\pages\parametre');





Route::get('/login', [UserController::class, 'LoginFrom'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'RegisterFrom'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/email/verify', [UserController::class, 'verifyNotice'])->name('verification.notice');
Route::get('/verify/{token}', [UserController::class, 'verifyAccount'])->name('verify.account');
Route::post('/email/resend', [UserController::class, 'resendVerification'])->name('verification.resend');
Route::get('/auth/{provider}', [UserController::class, 'redirectToProvider'])->name('social.login');
Route::get('/auth/{provider}/callback', [UserController::class, 'handleProviderCallback']);
Route::get('/two-factor', [UserController::class, 'TwoFactorForm'])->name('two-factor.show');
Route::post('/two-factor', [UserController::class, 'verifyTwoFactor'])->name('two-factor.verify');
Route::post('/send-password-reset-link',[UserController::class,'sendPasswordResetLink'])->name('send-password-reset-link');

// Activation du mode Propriétaire
Route::post('/activate-proprietaire', [UserController::class, 'activateProprietaire'])
     ->middleware(['auth', 'verified'])
     ->name('proprietaire.activate');
// routes/web.php
Route::prefix('proprietaire')->middleware(['auth', 'proprietaire.verified','role.check:particulier'])->group(function () {
    Route::get('/dashboard', [ProprietaireController::class, 'ProprietaireDashboard'])->name('proprietaire.dashboard');
    Route::get('/annonces/create', [AnnonceController::class, 'create'])->name('annonces.create');
});

// Création d'Entreprise
Route::post('/entreprise/create', [UserController::class, 'createEnterprise'])
     ->middleware(['auth', 'verified', 'role:admin_entreprise'])
     ->name('entreprise.create');

     // Routes pour le changement de type de compte
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/become-proprietaire', [AccountTypeController::class, 'becomeProprietaire'])
         ->name('become.proprietaire');
         
    Route::post('/create-entreprise', [AccountTypeController::class, 'createEnterprise'])
         ->name('create.entreprise');
});

Route::middleware(['auth','role.check:client'])->group(function () {
    Route::get('/home', [UserController::class, 'HomeDashboard'])->name('dashboard');
    Route::post('/logout',[UserController::class,'logoutHandler'])->name('logout');
});




require __DIR__.'/admin.php';
