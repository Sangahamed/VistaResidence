<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\MapController;

// use App\Http\Controllers\User\PropertyController;
// use App\Http\Controllers\User\LeadController;
// use App\Http\Controllers\User\AgencyController;
// use App\Http\Controllers\User\AgentController;
// use App\Http\Controllers\User\PropertyVisitController;
// use App\Http\Controllers\User\UserController;

// use App\Http\Controllers\User\NotificationController;
// use App\Http\Controllers\PropertyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes publiques




// Routes de recherche publiques
// Route::get('/properties', [PropertyController::class, 'index']);
// Route::get('/properties/{property}', [PropertyController::class, 'show']);
// Route::get('/properties/search', [PropertyController::class, 'search']);
// Route::get('/agencies', [AgencyController::class, 'index']);
// Route::get('/agencies/{agency}', [AgencyController::class, 'show']);
// Route::get('/agents', [AgentController::class, 'index']);
// Route::get('/agents/{agent}', [AgentController::class, 'show']);

Route::prefix('map')->group(function() {
    Route::get('/properties', [MapController::class, 'getPropertiesGeoJson']);
    Route::get('/points-of-interest', [MapController::class, 'getPointsOfInterestGeoJson']);
    Route::get('/price-range', [MapController::class, 'getPriceRange']);
});

// Routes protégées par authentification
// Route::middleware('auth:sanctum')->group(function () {
//     // Informations utilisateur
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });
    
    
    
//     // Profil utilisateur
//     Route::get('/profile', [UserController::class, 'profile']);
//     Route::put('/profile', [UserController::class, 'updateProfile']);
//     Route::put('/profile/preferences', [UserController::class, 'updatePreferences']);
    
//     // Notifications
//     Route::get('/notifications', [NotificationController::class, 'index']);
//     Route::put('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
//     Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
    
//     // Propriétés favorites
//     Route::post('/properties/{property}/favorite', [PropertyController::class, 'favorite']);
//     Route::get('/favorites', [PropertyController::class, 'favorites']);
    
//     // Demandes de visite
//     Route::apiResource('visits', PropertyVisitController::class);
//     Route::post('/visits/{visit}/confirm', [PropertyVisitController::class, 'confirm']);
//     Route::post('/visits/{visit}/complete', [PropertyVisitController::class, 'complete']);
//     Route::post('/visits/{visit}/cancel', [PropertyVisitController::class, 'cancel']);
//     Route::post('/visits/{visit}/note', [PropertyVisitController::class, 'addNote']);
//     Route::get('/calendar', [PropertyVisitController::class, 'calendar']);
    
//     // Routes pour les utilisateurs avec rôle agent ou supérieur
//     Route::middleware(['role:agent,agency_admin,admin,super_admin'])->group(function () {
//         // Gestion des propriétés
        
//         // Gestion des leads
//         Route::apiResource('leads', LeadController::class);
//         Route::post('/leads/{lead}/activity', [LeadController::class, 'addActivity']);
//         Route::post('/leads/activity/{activity}/complete', [LeadController::class, 'completeActivity']);
//         Route::delete('/leads/activity/{activity}', [LeadController::class, 'deleteActivity']);
//         Route::post('/leads/{lead}/assign', [LeadController::class, 'assign']);
//         Route::post('/leads/{lead}/status', [LeadController::class, 'changeStatus']);
//     });
    
//     // Routes pour les utilisateurs avec rôle admin d'agence ou supérieur
//     Route::middleware(['role:agency_admin,admin,super_admin'])->group(function () {
//         // Gestion des agents
//         Route::apiResource('agents', AgentController::class)->except(['index', 'show']);
//         Route::get('/agents/{agent}/properties', [AgentController::class, 'properties']);
//         Route::get('/agents/{agent}/leads', [AgentController::class, 'leads']);
//     });
    
//     // Routes pour les utilisateurs avec rôle admin d'entreprise ou supérieur
//     Route::middleware(['role:admin,super_admin'])->group(function () {
//         // Gestion des agences
//         Route::apiResource('agencies', AgencyController::class)->except(['index', 'show']);
//         Route::get('/agencies/{agency}/agents', [AgencyController::class, 'agents']);
//         Route::get('/agencies/{agency}/properties', [AgencyController::class, 'properties']);
//         Route::post('/agencies/{agency}/agents', [AgencyController::class, 'addAgent']);
//         Route::put('/agencies/{agency}/agents/{user}', [AgencyController::class, 'updateAgent']);
//         Route::delete('/agencies/{agency}/agents/{user}', [AgencyController::class, 'removeAgent']);
        
    
//     });
    
//     // Routes pour les utilisateurs avec rôle super admin
//     Route::middleware(['role:super_admin'])->group(function () {
//         // Gestion des utilisateurs
//         Route::apiResource('users', UserController::class);
        
//         // Paramètres du système
//         // Route::get('/settings', [SettingsController::class, 'index']);
//         // Route::put('/settings', [SettingsController::class, 'update']);
//         // Route::apiResource('settings/modules', ModuleController::class);
//     });
// });