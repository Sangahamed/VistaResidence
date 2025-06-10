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
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\LocationController;
use App\Livewire\MapManager;
use App\Livewire\MapFilters;



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

// Home & Public Content
Route::get('/api/get-location', function () {
    $geoService = new App\Services\EnhancedGeoLocationService();
    return response()->json($geoService->getLocationFromIP());
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/InfoPropriete/{property:slug}', 'detail')->name('detail');
});
Route::post('/store-position', [LocationController::class, 'store']);

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


// Agency & Agent Public Profiles
Route::prefix('agencies')->group(function () {
    Route::get('/', [AgencyController::class, 'publicIndex'])->name('agencies.public.index');
    Route::get('/{agency}', [AgencyController::class, 'publicShow'])->name('agencies.public.show');
});

Route::prefix('agents')->group(function () {
    Route::get('/', [AgentController::class, 'publicIndex'])->name('agents.public.index');
    Route::get('/{agent}', [AgentController::class, 'publicShow'])->name('agents.public.show');
});

// Tools
Route::prefix('tools')->group(function () {
    // Mortgage Calculator
    Route::prefix('mortgage')->name('mortgage.')->group(function () {
        Route::get('/', [MortgageCalculatorController::class, 'publicIndex'])->name('calculator');
        Route::post('/calculate', [MortgageCalculatorController::class, 'publicCalculate'])->name('calculate');
    });
});

Route::get('/map', [MapController::class, 'index'])->name('map.index');







// ======================
// AUTHENTICATED ROUTES
// ======================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/client', [DashboardController::class, 'clientDashboard'])->name('dashboard.client');
        Route::get('/individual', [DashboardController::class, 'individualDashboard'])->name('dashboard.individual');
        Route::get('/company', [DashboardController::class, 'companyDashboard'])->name('dashboard.company');
        Route::post('/switch-to-individual', [DashboardController::class, 'switchToIndividual'])->name('switch.to.individual');

        // Agency Dashboard
        Route::prefix('agency')->group(function () {
            Route::get('/', [AgencyDashboardController::class, 'index'])->name('dashboard.agency');
        });
    });

    // Property Routes
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/', [PropertyController::class, 'index'])->name('index');
        Route::get('/{property}', [PropertyController::class, 'show'])->name('show');
        Route::get('/search', [PropertySearchController::class, 'search'])->name('search');

        // Property Comparison
        Route::prefix('comparison')->name('comparison.')->group(function () {
            Route::get('/co', [PropertyComparisonController::class, 'index'])->name('index');
            Route::post('/add/{property}', [PropertyComparisonController::class, 'add'])->name('add');
            Route::post('/remove/{property}', [PropertyComparisonController::class, 'remove'])->name('remove');
            Route::post('/clear', [PropertyComparisonController::class, 'clear'])->name('clear');
        });
    });

    // Messages
    Route::post('/properties/{property}/start-conversation', [PropertyMessageController::class, 'startConversation'])
        ->name('properties.startConversation');

    // Recommendations
    Route::prefix('recommendations')->group(function () {
        Route::get('/', [RecommendationController::class, 'index'])->name('recommendations.index');
        Route::get('/similar/{property}', [RecommendationController::class, 'similarProperties'])->name('recommendations.similar');
        Route::get('/preferences', [RecommendationController::class, 'editPreferences'])->name('recommendations.preferences');
        Route::put('/preferences', [RecommendationController::class, 'updatePreferences'])->name('recommendations.update');
        Route::post('/record-view/{property}', [RecommendationController::class, 'recordView'])->name('recommendations.record-view');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('index');
        Route::put('/', [UserController::class, 'updateProfile'])->name('update');
        Route::put('/preferences', [UserController::class, 'updatePreferences'])->name('preferences.update');
    });

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/count', [NotificationController::class, 'getUnreadCount']);
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/preferences', [NotificationController::class, 'showPreferences'])->name('preferences');
        Route::patch('/preferences', [NotificationController::class, 'updatePreferences'])->name('preferences.update');
    });

    // Property Management
    Route::prefix('my-properties')->name('properties.')->group(function () {
        Route::get('/', [PropertyController::class, 'userProperties'])->name('user.index');
        Route::get('/create', [PropertyController::class, 'create'])->name('create');
        Route::post('/', [PropertyController::class, 'store'])->name('store');
        Route::get('/{property}/edit', [PropertyController::class, 'edit'])->name('edit');
        Route::get('/{property}/media', [PropertyController::class, 'editMedia'])->name('media');
        Route::put('/{property}', [PropertyController::class, 'update'])->name('update');
        Route::delete('/{property}', [PropertyController::class, 'destroy'])->name('destroy');
        Route::get('/{property}/visits', [PropertyController::class, 'visits'])->name('visits');
        Route::get('/{property}/statistics', [PropertyStatisticsController::class, 'show'])
            ->name('statistics')
            ->middleware(['permission:view-statistics']);

        // Virtual Tours
        Route::prefix('{property}/virtual-tour')->name('virtual-tour.')->group(function () {
            Route::get('/', [VirtualTourController::class, 'show'])->name('show');
            Route::middleware(['permission:manage-virtual-tours'])->group(function () {
                Route::get('/edit', [VirtualTourController::class, 'edit'])->name('edit');
                Route::put('/', [VirtualTourController::class, 'update'])->name('update');
                Route::post('/basic', [VirtualTourController::class, 'createBasicTour'])->name('basic');
                Route::delete('/', [VirtualTourController::class, 'destroy'])->name('destroy');
            });
        });
    });

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{property}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/reviews/{property}', [ReviewController::class, 'store'])->name('reviews.store');

    // Property Visits
    Route::get('/visits/calendar', [PropertyVisitController::class, 'calendar'])->name('visits.calendar');
    // Mise à jour du statut
    Route::patch('visits/{visit}/update-status', [PropertyVisitController::class, 'updateStatus'])
        ->name('visits.update-status');

    // Annulation (avec méthode POST)
    Route::get('/visits/{visit}/cancel', [PropertyVisitController::class, 'cancelForm'])->name('visits.cancel.form');
    Route::post('/visits/{visit}/cancel', [PropertyVisitController::class, 'cancel'])->name('visits.cancel');
    Route::prefix('visits')->name('visits.')->group(function () {
        Route::get('/', [PropertyVisitController::class, 'index'])->name('index');
        Route::get('/create', [PropertyVisitController::class, 'create'])->name('create');
        Route::post('/', [PropertyVisitController::class, 'store'])->name('store');
        Route::get('/{visit}', [PropertyVisitController::class, 'show'])->name('show');
        Route::get('/{visit}/edit', [PropertyVisitController::class, 'edit'])->name('edit');
        Route::put('/{visit}', [PropertyVisitController::class, 'update'])->name('update');
        Route::delete('/{visit}', [PropertyVisitController::class, 'destroy'])->name('destroy');

        // Actions supplémentaires
        Route::post('/{visit}/confirm', [PropertyVisitController::class, 'confirm'])->name('confirm');
        Route::post('/{visit}/complete', [PropertyVisitController::class, 'complete'])->name('complete');
        // Route::post('/{visit}/cancel', [PropertyVisitController::class, 'cancel'])->name('cancel');
        Route::post('/{visit}/note', [PropertyVisitController::class, 'addNote'])->name('add-note');

        // Calendrier

        Route::get('/calendar/events', [PropertyVisitController::class, 'calendarEvents'])->name('calendar.events');
    });

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

    // Companies
    Route::prefix('companies')->name('companies.')->group(function () {
    Route::get('/pending', [CompanyController::class, 'pending'])->name('pending'); // Déplacé ici
    Route::get('/', [CompanyController::class, 'index'])->name('index');
    Route::get('/create', [CompanyController::class, 'create'])->name('create');
    Route::post('/', [CompanyController::class, 'store'])->name('store');
    Route::get('/{company}', [CompanyController::class, 'show'])->name('show');
    Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit');
    Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
    Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
});


    // Auctions
    Route::prefix('auctions')->name('auctions.')->group(function () {
        Route::get('/', [AuctionController::class, 'index'])->name('index');
        Route::get('/history', [AuctionController::class, 'userHistory'])->name('history');
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
    Route::middleware(['role:agency_admin,admin'])->group(function () {
        // Agents Management
        Route::prefix('agency/agents')->name('agents.')->group(function () {
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

        // Agency Reports
        Route::prefix('agency/reports')->name('agency.reports.')->group(function () {
            Route::get('/', [ReportController::class, 'agencyIndex'])->name('index');
        });
    });

    // ======================
    // COMPANY ADMIN ROUTES
    // ======================
    Route::middleware(['role:admin'])->group(function () {
        // Agencies Management
        Route::prefix('management/agencies')->name('agencies.')->group(function () {
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

        // Company Reports
        Route::prefix('company/reports')->name('company.reports.')->group(function () {
            Route::get('/', [ReportController::class, 'companyIndex'])->name('index');
        });
    });

    // ======================
    // SUPER ADMIN ROUTES
    // ======================
    Route::middleware(['role:super_admin'])->group(function () {
        // Users Management
        Route::prefix('management/users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // System Reports
        Route::prefix('system/reports')->name('system.reports.')->group(function () {
            Route::get('/', [ReportController::class, 'systemIndex'])->name('index');
        });
    });
});

// Help Pages

Route::get('/help/virtual-tour-guide', function () {
    return redirect('https://support.google.com/maps/answer/7011737');
})->name('help.virtual-tour-guide');
