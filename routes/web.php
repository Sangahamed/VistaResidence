<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PropertyController;
use App\Http\Controllers\User\LeadController;
use App\Http\Controllers\User\AgencyController;
use App\Http\Controllers\User\AgentController;
use App\Http\Controllers\User\PropertyMessageController;
use App\Http\Controllers\User\PropertyVisitController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\CompanyController;
use App\Http\Controllers\User\VirtualTourController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\TeamController;
use App\Http\Controllers\User\TeamMemberController;
use App\Http\Controllers\User\ProjectController;
use App\Http\Controllers\User\TaskController;
use App\Http\Controllers\User\ModuleController;
use App\Http\Controllers\User\InvitationController;
use App\Http\Controllers\User\AuctionController;
use App\Http\Controllers\User\MortgageController;
use App\Http\Controllers\User\RecommendationController;
use App\Http\Controllers\User\PropertySearchController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\PropertyStatisticsController;
use App\Http\Controllers\User\MapController;
use App\Http\Controllers\User\AgencyDashboardController;
use App\Http\Controllers\User\MarketingController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\PropertyComparisonController;
use App\Http\Controllers\User\MortgageCalculatorController;
use App\Http\Controllers\User\CalendarController;

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

// ======================
// PUBLIC ROUTES
// ======================

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'RegisterFrom')->name('register');
    Route::post('/register', 'register');
    Route::get('/email/verify', 'verifyNotice')->name('verification.notice');
    Route::get('/verify/{token}', 'verifyAccount')->name('verify.account');
    Route::post('/email/resend', 'resendVerification')->name('verification.resend');
    Route::get('/login', 'LoginFrom')->name('login');
    Route::post('/login', 'login');
    Route::get('/auth/{provider}', 'redirectToProvider')->name('social.login');
    Route::get('/auth/{provider}/callback', 'handleProviderCallback');
    Route::get('/two-factor', 'TwoFactorForm')->name('two-factor.show');
    Route::post('/two-factor', 'verifyTwoFactor')->name('two-factor.verify');
    Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('send-password-reset-link');
});

// Home & Property Routes
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/InfoPropriete', 'detail')->name('detail');
});

// Public Property Routes
Route::controller(PropertyController::class)->group(function () {
    Route::get('/properties/{property}', 'show')->name('properties.show');
    Route::get('/properties/search', 'search')->name('properties.search');
});

// Public Agency Routes
Route::controller(AgencyController::class)->group(function () {
    Route::get('/agencies', 'publicIndex')->name('agencies.public.index');
    Route::get('/agencies/{agency}', 'publicShow')->name('agencies.public.show');
});

// Public Agent Routes
Route::controller(AgentController::class)->group(function () {
    Route::get('/agents', 'publicIndex')->name('agents.public.index');
    Route::get('/agents/{agent}', 'publicShow')->name('agents.public.show');
});

// Mortgage Calculator
Route::controller(MortgageCalculatorController::class)->prefix('calculateur')->name('mortgage.')->group(function () {
    Route::get('/', 'publicIndex')->name('calculator');
    Route::post('/calculate', 'publicCalculate')->name('calculate');
});

// Maps & Geolocation
Route::prefix('maps')->name('maps.')->group(function () {
    Route::get('/', [MapController::class, 'index'])->name('index');
    Route::get('/properties', [MapController::class, 'getPropertiesGeoJson'])->name('properties.geojson');
    Route::get('/pois', [MapController::class, 'getPointsOfInterestGeoJson'])->name('pois.geojson');
    Route::get('/properties/{property}', [MapController::class, 'showProperty'])->name('property');
});

// Recommendations
Route::prefix('recommendations')->group(function() {
    Route::get('/', [RecommendationController::class, 'index'])->name('recommendations.index');
    Route::get('/similar/{property}', [RecommendationController::class, 'similarProperties'])->name('recommendations.similar');
    Route::get('/preferences', [RecommendationController::class, 'editPreferences'])->name('recommendations.preferences');
    Route::put('/preferences', [RecommendationController::class, 'updatePreferences'])->name('recommendations.update');
    Route::post('/record-view/{property}', [RecommendationController::class, 'recordView'])->name('recommendations.record-view');
});

// Messages
// Route::prefix('messages')->name('messages.')->group(function () {
    Route::post('/properties/{property}/start-conversation', [PropertyMessageController::class, 'startConversation'])
        ->name('properties.startConversation');

    Route::get('/agents', [PropertyMessageController::class, 'showAgents'])->name('agents.list');
// });

// ======================
// AUTHENTICATED ROUTES
// ======================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Routes
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/dashboard/client', 'clientDashboard')->name('dashboard.client');
        Route::get('/dashboard/individual', 'individualDashboard')->name('dashboard.individual');
        Route::get('/dashboard/company', 'companyDashboard')->name('dashboard.company');
        Route::post('/switch-to-individual', 'switchToIndividual')->name('switch.to.individual');
    });

    // User Profile
    Route::controller(UserController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'profile')->name('index');
        Route::put('/', 'updateProfile')->name('update');
        Route::put('/preferences', 'updatePreferences')->name('preferences.update');
    });

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read.all');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/preferences', [NotificationController::class, 'preferences'])->name('preferences');
        Route::patch('/preferences', [NotificationController::class, 'updatePreferences'])->name('preferences.update');
    });

    // Property Management
    Route::get('/properties-create', [PropertyController::class, 'create'])->name('properties.create');
    Route::prefix('properties')->name('properties.')->group(function () {
        // Property CRUD
        Route::get('/', [PropertyController::class, 'index'])->name('index');

        Route::post('/', [PropertyController::class, 'store'])->name('store');
        Route::get('/{property}/edit', [PropertyController::class, 'edit'])->name('edit');
        Route::get('/{property}/media', [PropertyController::class, 'editMedia'])->name('media');
        Route::put('/{property}', [PropertyController::class, 'update'])->name('update');
        Route::delete('/{property}', [PropertyController::class, 'destroy'])->name('destroy');
        Route::get('/{property}/visits', [PropertyController::class, 'visits'])->name('visits');
        Route::get('/{property}/statistics', [PropertyStatisticsController::class, 'show'])
            ->name('statistics')
            ->middleware(['permission:view-statistics']);

        // Favorites
        Route::post('/{property}/favorite', [PropertyController::class, 'favorite'])->name('favorite');
        Route::get('/favorites', [PropertyController::class, 'favorites'])->name('favorites');

        // Virtual Tours
        Route::get('/{property}/virtual-tour', [VirtualTourController::class, 'show'])->name('virtual-tour');
        Route::middleware(['permission:manage-virtual-tours'])->group(function () {
            Route::get('/{property}/virtual-tour/edit', [VirtualTourController::class, 'edit'])->name('virtual-tour.edit');
            Route::put('/{property}/virtual-tour', [VirtualTourController::class, 'update'])->name('virtual-tour.update');
            Route::post('/{property}/virtual-tour/basic', [VirtualTourController::class, 'createBasicTour'])->name('virtual-tour.basic');
            Route::delete('/{property}/virtual-tour', [VirtualTourController::class, 'destroy'])->name('virtual-tour.destroy');
        });

        // Property Comparison
        Route::get('/comparison', [PropertyComparisonController::class, 'index'])->name('comparison');
        Route::post('/{property}/comparison/add', [PropertyComparisonController::class, 'add'])->name('comparison.add');
        Route::delete('/{property}/comparison/remove', [PropertyComparisonController::class, 'remove'])->name('comparison.remove');
        Route::delete('/comparison/clear', [PropertyComparisonController::class, 'clear'])->name('comparison.clear');
    });

    // Property Visits
    Route::prefix('visits')->name('properties.visits.')->group(function () {
        Route::get('/', [PropertyVisitController::class, 'index'])->name('index');
        Route::get('/create', [PropertyVisitController::class, 'create'])->name('create');
        Route::post('/', [PropertyVisitController::class, 'store'])->name('store');
        Route::get('/{visit}', [PropertyVisitController::class, 'show'])->name('show');
        Route::get('/{visit}/edit', [PropertyVisitController::class, 'edit'])->name('edit');
        Route::put('/{visit}', [PropertyVisitController::class, 'update'])->name('update');
        Route::delete('/{visit}', [PropertyVisitController::class, 'destroy'])->name('destroy');
        Route::post('/{visit}/confirm', [PropertyVisitController::class, 'confirm'])->name('confirm');
        Route::post('/{visit}/complete', [PropertyVisitController::class, 'complete'])->name('complete');
        Route::post('/{visit}/cancel', [PropertyVisitController::class, 'cancel'])->name('cancel');
        Route::post('/{visit}/note', [PropertyVisitController::class, 'addNote'])->name('add-note');
        Route::get('/calendar', [PropertyVisitController::class, 'calendar'])->name('calendar');
    });

    // Route::middleware(['accescompany'])->group(function () {
        // Création entreprise
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
        Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        // Page d'attente
        Route::get('/entreprise/attente', [CompanyController::class, 'pending'])->name('companies.pending');

        
        // Autres routes...
    // });


    // Leads Management
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [LeadController::class, 'index'])->name('index');
        Route::get('/create', [LeadController::class, 'create'])->name('create');
        Route::post('/', [LeadController::class, 'store'])->name('store');
        Route::get('/{lead}', [LeadController::class, 'show'])->name('show');
        Route::get('/{lead}/edit', [LeadController::class, 'edit'])->name('edit');
        Route::put('/{lead}', [LeadController::class, 'update'])->name('update');
        Route::delete('/{lead}', [LeadController::class, 'destroy'])->name('destroy');
        Route::post('/{lead}/activity', [LeadController::class, 'addActivity'])->name('add-activity');
        Route::post('/activity/{activity}/complete', [LeadController::class, 'completeActivity'])->name('complete-activity');
        Route::delete('/activity/{activity}', [LeadController::class, 'deleteActivity'])->name('delete-activity');
        Route::post('/{lead}/assign', [LeadController::class, 'assign'])->name('assign');
        Route::post('/{lead}/status', [LeadController::class, 'changeStatus'])->name('change-status');
    });

    // Auctions
    Route::get('/auctions/history', [AuctionController::class, 'userHistory'])->name('auctions.history');
    Route::prefix('auctions')->name('auctions.')->group(function () {
        Route::get('/', [AuctionController::class, 'index'])->name('index');
        Route::get('/{auction}', [AuctionController::class, 'show'])->name('show');

        
        // Participation
        Route::middleware(['permission:participate-auctions'])->group(function () {
            Route::post('/{auction}/bid', [AuctionController::class, 'placeBid'])->name('bid');
        });
        
        // Management
        Route::middleware(['permission:manage-auctions'])->group(function () {
            Route::get('/create', [AuctionController::class, 'create'])->name('create');
            Route::post('/', [AuctionController::class, 'store'])->name('store');
            Route::get('/{auction}/edit', [AuctionController::class, 'edit'])->name('edit');
            Route::put('/{auction}', [AuctionController::class, 'update'])->name('update');
            Route::delete('/{auction}', [AuctionController::class, 'destroy'])->name('destroy');
        });
    });

    // ======================
    // AGENCY ADMIN ROUTES
    // ======================
    Route::middleware(['role:agency_admin,admin,super_admin'])->group(function () {
        // Agents Management
        Route::prefix('agents')->name('agents.')->group(function () {
            Route::get('/', [AgentController::class, 'index'])->name('index');
            Route::get('/create', [AgentController::class, 'create'])->name('create');
            Route::post('/', [AgentController::class, 'store'])->name('store');
            Route::get('/{agent}', [AgentController::class, 'show'])->name('show');
            Route::get('/{agent}/edit', [AgentController::class, 'edit'])->name('edit');
            Route::put('/{agent}', [AgentController::class, 'update'])->name('update');
            Route::delete('/{agent}', [AgentController::class, 'destroy'])->name('destroy');
            Route::get('/{agent}/properties', [AgentController::class, 'properties'])->name('properties');
            Route::get('/{agent}/leads', [AgentController::class, 'leads'])->name('leads');
        });
    });

    // ======================
    // COMPANY ADMIN ROUTES
    // ======================
    Route::middleware(['role:admin,super_admin'])->group(function () {
        // Agencies Management
        Route::prefix('agencies')->name('agencies.')->group(function () {
            Route::get('/', [AgencyController::class, 'index'])->name('index');
            Route::get('/create', [AgencyController::class, 'create'])->name('create');
            Route::post('/', [AgencyController::class, 'store'])->name('store');
            Route::get('/{agency}', [AgencyController::class, 'show'])->name('show');
            Route::get('/{agency}/edit', [AgencyController::class, 'edit'])->name('edit');
            Route::put('/{agency}', [AgencyController::class, 'update'])->name('update');
            Route::delete('/{agency}', [AgencyController::class, 'destroy'])->name('destroy');
            Route::get('/{agency}/agents', [AgencyController::class, 'agents'])->name('agents');
            Route::get('/{agency}/properties', [AgencyController::class, 'properties'])->name('properties');
            Route::post('/{agency}/agents', [AgencyController::class, 'addAgent'])->name('add-agent');
            Route::put('/{agency}/agents/{user}', [AgencyController::class, 'updateAgent'])->name('update-agent');
            Route::delete('/{agency}/agents/{user}', [AgencyController::class, 'removeAgent'])->name('remove-agent');
        });

       
    });

    // ======================
    // SUPER ADMIN ROUTES
    // ======================
    Route::middleware(['role:super_admin'])->group(function () {
        // Users Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
    });
});

// Help Pages
Route::get('/help/virtual-tour-guide', function () {
    return view('properties.virtual-tour-guide');
})->name('properties.virtual-tour-guide');