<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PropertyVisitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
// use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\ContactController;
// use App\Http\Controllers\SearchController;
// use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;

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

// Routes publiques
// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/about', [HomeController::class, 'about'])->name('about');
// Route::get('/contact', [ContactController::class, 'index'])->name('contact');
// Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Routes de recherche
// Route::get('/search', [SearchController::class, 'index'])->name('search');
// Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Routes pour les propriétés publiques
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/properties/search', [PropertyController::class, 'search'])->name('properties.search');

// Routes pour les agences publiques
Route::get('/agencies', [AgencyController::class, 'publicIndex'])->name('agencies.public.index');
Route::get('/agencies/{agency}', [AgencyController::class, 'publicShow'])->name('agencies.public.show');

// Routes pour les agents publics
Route::get('/agents', [AgentController::class, 'publicIndex'])->name('agents.public.index');
Route::get('/agents/{agent}', [AgentController::class, 'publicShow'])->name('agents.public.show');



// Routes protégées par authentification
Route::middleware(['auth', 'verified'])->group(function () {
    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil utilisateur
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/preferences', [UserController::class, 'updatePreferences'])->name('profile.preferences.update');
    

    

    // Routes pour les notifications
    Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read.all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/preferences', [NotificationController::class, 'preferences'])->name('preferences');
        Route::patch('/preferences', [NotificationController::class, 'updatePreferences'])->name('preferences.update');
    });
    // Propriétés favorites
    Route::post('/properties/{property}/favorite', [PropertyController::class, 'favorite'])->name('properties.favorite');
    Route::get('/favorites', [PropertyController::class, 'favorites'])->name('properties.favorites');
    
    // Demandes de visite
    Route::get('/visits', [PropertyVisitController::class, 'index'])->name('visits.index');
    Route::get('/visits/create', [PropertyVisitController::class, 'create'])->name('visits.create');
    Route::post('/visits', [PropertyVisitController::class, 'store'])->name('visits.store');
    Route::get('/visits/{visit}', [PropertyVisitController::class, 'show'])->name('visits.show');
    Route::get('/visits/{visit}/edit', [PropertyVisitController::class, 'edit'])->name('visits.edit');
    Route::put('/visits/{visit}', [PropertyVisitController::class, 'update'])->name('visits.update');
    Route::delete('/visits/{visit}', [PropertyVisitController::class, 'destroy'])->name('visits.destroy');
    Route::post('/visits/{visit}/confirm', [PropertyVisitController::class, 'confirm'])->name('visits.confirm');
    Route::post('/visits/{visit}/complete', [PropertyVisitController::class, 'complete'])->name('visits.complete');
    Route::post('/visits/{visit}/cancel', [PropertyVisitController::class, 'cancel'])->name('visits.cancel');
    Route::post('/visits/{visit}/note', [PropertyVisitController::class, 'addNote'])->name('visits.add-note');
    Route::get('/calendar', [PropertyVisitController::class, 'calendar'])->name('visits.calendar');
    
    // Routes pour les utilisateurs avec rôle agent ou supérieur
    Route::middleware(['role:agent,agency_admin,admin,super_admin'])->group(function () {
        // Gestion des propriétés
        Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
        Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
        Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
        Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
        Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
        
        // Gestion des leads
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
        Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
        Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
        Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
        Route::post('/leads/{lead}/activity', [LeadController::class, 'addActivity'])->name('leads.add-activity');
        Route::post('/leads/activity/{activity}/complete', [LeadController::class, 'completeActivity'])->name('leads.complete-activity');
        Route::delete('/leads/activity/{activity}', [LeadController::class, 'deleteActivity'])->name('leads.delete-activity');
        Route::post('/leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');
        Route::post('/leads/{lead}/status', [LeadController::class, 'changeStatus'])->name('leads.change-status');
    });
    
    // Routes pour les utilisateurs avec rôle admin d'agence ou supérieur
    Route::middleware(['role:agency_admin,admin,super_admin'])->group(function () {
        // Gestion des agents
        Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
        Route::get('/agents/create', [AgentController::class, 'create'])->name('agents.create');
        Route::post('/agents', [AgentController::class, 'store'])->name('agents.store');
        Route::get('/agents/{agent}', [AgentController::class, 'show'])->name('agents.show');
        Route::get('/agents/{agent}/edit', [AgentController::class, 'edit'])->name('agents.edit');
        Route::put('/agents/{agent}', [AgentController::class, 'update'])->name('agents.update');
        Route::delete('/agents/{agent}', [AgentController::class, 'destroy'])->name('agents.destroy');
        Route::get('/agents/{agent}/properties', [AgentController::class, 'properties'])->name('agents.properties');
        Route::get('/agents/{agent}/leads', [AgentController::class, 'leads'])->name('agents.leads');
    });
    
    // Routes pour les utilisateurs avec rôle admin d'entreprise ou supérieur
    Route::middleware(['role:admin,super_admin'])->group(function () {
        // Gestion des agences
        Route::get('/agencies', [AgencyController::class, 'index'])->name('agencies.index');
        Route::get('/agencies/create', [AgencyController::class, 'create'])->name('agencies.create');
        Route::post('/agencies', [AgencyController::class, 'store'])->name('agencies.store');
        Route::get('/agencies/{agency}', [AgencyController::class, 'show'])->name('agencies.show');
        Route::get('/agencies/{agency}/edit', [AgencyController::class, 'edit'])->name('agencies.edit');
        Route::put('/agencies/{agency}', [AgencyController::class, 'update'])->name('agencies.update');
        Route::delete('/agencies/{agency}', [AgencyController::class, 'destroy'])->name('agencies.destroy');
        Route::get('/agencies/{agency}/agents', [AgencyController::class, 'agents'])->name('agencies.agents');
        Route::get('/agencies/{agency}/properties', [AgencyController::class, 'properties'])->name('agencies.properties');
        Route::post('/agencies/{agency}/agents', [AgencyController::class, 'addAgent'])->name('agencies.add-agent');
        Route::put('/agencies/{agency}/agents/{user}', [AgencyController::class, 'updateAgent'])->name('agencies.update-agent');
        Route::delete('/agencies/{agency}/agents/{user}', [AgencyController::class, 'removeAgent'])->name('agencies.remove-agent');
        
        // Gestion des entreprises
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
        Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::get('/companies/{company}/users', [CompanyController::class, 'users'])->name('companies.users');
        Route::get('/companies/{company}/agencies', [CompanyController::class, 'agencies'])->name('companies.agencies');
        Route::get('/companies/{company}/modules', [CompanyController::class, 'modules'])->name('companies.modules');
        Route::post('/companies/{company}/users', [CompanyController::class, 'addUser'])->name('companies.add-user');
        Route::put('/companies/{company}/users/{user}', [CompanyController::class, 'updateUser'])->name('companies.update-user');
        Route::delete('/companies/{company}/users/{user}', [CompanyController::class, 'removeUser'])->name('companies.remove-user');
        Route::post('/companies/{company}/modules', [CompanyController::class, 'addModule'])->name('companies.add-module');
        Route::put('/companies/{company}/modules/{module}', [CompanyController::class, 'updateModule'])->name('companies.update-module');
        Route::delete('/companies/{company}/modules/{module}', [CompanyController::class, 'removeModule'])->name('companies.remove-module');
        Route::post('/companies/{company}/approve', [CompanyController::class, 'approve'])->name('companies.approve');
        Route::post('/companies/{company}/reject', [CompanyController::class, 'reject'])->name('companies.reject');
    });
    
    // Routes pour les utilisateurs avec rôle super admin
    Route::middleware(['role:super_admin'])->group(function () {
        // Gestion des utilisateurs
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Paramètres du système
        // Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        // Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        // Route::get('/settings/modules', [SettingsController::class, 'modules'])->name('settings.modules');
        // Route::get('/settings/modules/create', [SettingsController::class, 'createModule'])->name('settings.modules.create');
        // Route::post('/settings/modules', [SettingsController::class, 'storeModule'])->name('settings.modules.store');
        // Route::get('/settings/modules/{module}/edit', [SettingsController::class, 'editModule'])->name('settings.modules.edit');
        // Route::put('/settings/modules/{module}', [SettingsController::class, 'updateModule'])->name('settings.modules.update');
        // Route::delete('/settings/modules/{module}', [SettingsController::class, 'destroyModule'])->name('settings.modules.destroy');
    });
});

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});