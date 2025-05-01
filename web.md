<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\CompanyController;
use App\Http\Controllers\User\TeamController;
use App\Http\Controllers\User\TeamMemberController;
use App\Http\Controllers\User\ProjectController;
use App\Http\Controllers\User\TaskController;
use App\Http\Controllers\User\ModuleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\InvitationController;
use App\Http\Controllers\User\PropertyController;
use App\Http\Controllers\User\LeadController;
use App\Http\Controllers\User\AgencyController;
use App\Http\Controllers\User\AgentController;
use App\Http\Controllers\User\AuctionController;
use App\Http\Controllers\User\MortgageController;
use App\Http\Controllers\User\RecommendationController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PropertySearchController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\PropertyMessageController;
use App\Http\Controllers\User\PropertyVisitController;
use App\Http\Controllers\User\PropertyStatisticsController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\MapController;
use App\Http\Controllers\User\VirtualTourController;
use App\Http\Controllers\User\AgencyDashboardController;
use App\Http\Controllers\User\MarketingController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\PropertyComparisonController;
use App\Http\Controllers\User\MortgageCalculatorController;
use App\Http\Controllers\User\CalendarController;

// use App\Http\Controllers\User\ProfileController;
// use App\Http\Controllers\User\PropertyAlertController;

/*
|--------------------------------------------------------------------------
| Routes accessibles sans authentification
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', function () {
    return view('welcome');
});



Route::view('/detail','front\pages\detail');
Route::view('/index','front\pages\index');


Route::view('/proprietes','back\user\propriete');
Route::view('/addproprietes','back\user\addpropriete');
Route::view('/paramettre','back\user\parametre');




Route::get('/register', [UserController::class, 'RegisterFrom'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'LoginFrom'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/email/verify', [UserController::class, 'verifyNotice'])->name('verification.notice');
Route::get('/verify/{token}', [UserController::class, 'verifyAccount'])->name('verify.account');
Route::post('/email/resend', [UserController::class, 'resendVerification'])->name('verification.resend');
Route::get('/auth/{provider}', [UserController::class, 'redirectToProvider'])->name('social.login');
Route::get('/auth/{provider}/callback', [UserController::class, 'handleProviderCallback']);
Route::get('/two-factor', [UserController::class, 'TwoFactorForm'])->name('two-factor.show');
Route::post('/two-factor', [UserController::class, 'verifyTwoFactor'])->name('two-factor.verify');
Route::post('/send-password-reset-link',[UserController::class,'sendPasswordResetLink'])->name('send-password-reset-link');



// Page d'accueil publique
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Recherche de propriétés publique
Route::get('/properties/search', [PropertySearchController::class, 'publicIndex'])->name('public.properties.search');
Route::get('/properties/search/results', [PropertySearchController::class, 'publicSearch'])->name('public.properties.search.results');

// Affichage des propriétés publiques
Route::get('/properties', [PropertyController::class, 'publicIndex'])->name('public.properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'publicShow'])->name('public.properties.show');

// Calculateur hypothécaire public
// Calculateur hypothécaire public
Route::get('/calculateur', [MortgageCalculatorController::class, 'publicIndex'])->name('mortgage.calculator');
Route::post('/calculateur/calculate', [MortgageCalculatorController::class, 'publicCalculate'])->name('mortgage.calculate');

// Invitations
Route::get('invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');
Route::post('invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
Route::post('invitations/{token}/decline', [InvitationController::class, 'decline'])->name('invitations.decline');

// Pages d'aide
Route::get('/help/virtual-tour-guide', function () {
    return view('properties.virtual-tour-guide');
})->name('properties.virtual-tour-guide');

/*
|--------------------------------------------------------------------------
| Routes nécessitant une authentification
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | Routes du tableau de bord - Accessibles à tous les utilisateurs authentifiés
    |--------------------------------------------------------------------------
    */
    Route::post('/logout',[UserController::class,'logoutHandler'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/messages', [DashboardController::class, 'messages'])->name('dashboard.messages');
    
    // Tableaux de bord spécifiques par type d'utilisateur
    Route::get('/dashboard/client', [DashboardController::class, 'clientDashboard'])->name('dashboard.client');
    Route::get('/dashboard/individual', [DashboardController::class, 'individualDashboard'])->name('dashboard.individual');
    Route::get('/dashboard/company', [DashboardController::class, 'companyDashboard'])->name('dashboard.company');

    // Switch account type
    Route::post('/switch-to-individual', [DashboardController::class, 'switchToIndividual'])->name('switch.to.individual');


    Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
    Route::post('/company', [CompanyController::class, 'store'])->name('company.store');
    Route::get('/company/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company', [CompanyController::class, 'update'])->name('company.update');
    
    // Notifications - Accessibles à tous les utilisateurs authentifiés
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/preferences', [NotificationController::class, 'showPreferences'])->name('preferences');
        Route::put('/preferences', [NotificationController::class, 'updatePreferences'])->name('preferences.update');
    });

    // Gestion des invitations - Nécessite permission
    Route::middleware(['permission:manage-invitations'])->prefix('invitations')->name('invitations.')->group(function () {
        Route::get('/create', [InvitationController::class, 'create'])->name('create');
        Route::post('/', [InvitationController::class, 'store'])->name('store');
        Route::get('/{invitation}', [InvitationController::class, 'show'])->name('show');
        Route::delete('/{invitation}', [InvitationController::class, 'destroy'])->name('destroy');
        Route::post('/{invitation}/resend', [InvitationController::class, 'resend'])->name('resend');
    });
    
    // Route spécifique pour les invitations d'entreprise
    // Route::get('/companies/{company}/invitations/create', [InvitationController::class, 'createForCompany'])
    //     ->name('invitations.create')
    //     ->middleware(['permission:manage-invitations']);

    
    
    /*
    |--------------------------------------------------------------------------
    | Routes pour la recherche et la consultation de propriétés
    |--------------------------------------------------------------------------
    */
    
    // Recherche de propriétés - Accessible à tous les utilisateurs authentifiés
    Route::prefix('properties/search')->name('properties.search.')->group(function () {
        Route::get('/', [PropertySearchController::class, 'index'])->name('index');
        Route::get('/results', [PropertySearchController::class, 'search'])->name('results');
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        // Recherches sauvegardées - Nécessite permission
        Route::middleware(['permission:save-searches'])->group(function () {
            Route::post('/save', [PropertySearchController::class, 'saveSearch'])->name('save');
            Route::delete('/{savedSearch}', [PropertySearchController::class, 'deleteSavedSearch'])->name('delete');
            Route::get('/load/{savedSearch}', [PropertySearchController::class, 'loadSavedSearch'])->name('load');
        });
    });
    
    // Consultation des propriétés - Accessible à tous les utilisateurs authentifiés
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/', [PropertyController::class, 'index'])->name('index');
        Route::get('/{property}', [PropertyController::class, 'show'])->name('show');
        
        // Favoris - Nécessite permission
        Route::middleware(['permission:manage-favorites'])->group(function () {
            Route::post('/{property}/favorite', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
            Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
        });
            // Comparaison de propriétés - Nécessite permission
            Route::middleware(['permission:compare-properties'])->prefix('comparison')->name('comparison.')->group(function () {
                Route::get('/', [PropertyComparisonController::class, 'index'])->name('index');
                Route::post('/{property}/add', [PropertyComparisonController::class, 'add'])->name('add');
                Route::delete('/{property}/remove', [PropertyComparisonController::class, 'remove'])->name('remove');
                Route::delete('/clear', [PropertyComparisonController::class, 'clear'])->name('clear');
            });
    
            // Route de comparaison de propriétés
            Route::get('/properties/comparison', [PropertyComparisonController::class, 'index'])->name('properties.comparison');
            Route::post('/properties/{property}/comparison/add', [PropertyComparisonController::class, 'add'])->name('properties.comparison.add');
            Route::delete('/properties/{property}/comparison/remove', [PropertyComparisonController::class, 'remove'])->name('properties.comparison.remove');
            Route::delete('/properties/comparison/clear', [PropertyComparisonController::class, 'clear'])->name('properties.comparison.clear');
        
        // Visites virtuelles - Accessible à tous les utilisateurs authentifiés
        Route::get('/{property}/virtual-tour', [VirtualTourController::class, 'show'])->name('virtual-tour');
        
        // Statistiques - Nécessite permission ou policy
        Route::get('/{property}/statistics', [PropertyStatisticsController::class, 'show'])
            ->name('statistics')
            ->middleware(['permission:view-statistics']);
        
        // Contact propriétaire/agent - Accessible à tous les utilisateurs authentifiés
        Route::post('/{property}/contact', [PropertyMessageController::class, 'startConversation'])->name('contact');
    });
    
    // Cartes et géolocalisation - Accessible à tous les utilisateurs authentifiés
    Route::prefix('maps')->name('maps.')->group(function () {
        Route::get('/', [MapController::class, 'index'])->name('index');
        Route::get('/properties', [MapController::class, 'getPropertiesGeoJson'])->name('properties.geojson');
        Route::get('/pois', [MapController::class, 'getPointsOfInterestGeoJson'])->name('pois.geojson');
        Route::get('/properties/{property}', [MapController::class, 'showProperty'])->name('property');
    });
    
    // Calculateur hypothécaire - Accessible à tous les utilisateurs authentifiés
    Route::prefix('calculateur')->name('mortgage.')->group(function () {
        Route::get('/', [MortgageCalculatorController::class, 'index'])->name('calculator');
        Route::post('/calculate', [MortgageCalculatorController::class, 'calculate'])->name('calculate');
    });
    
    // Recommandations - Accessible à tous les utilisateurs authentifiés
    Route::prefix('recommendations')->name('recommendations.')->group(function () {
        Route::get('/', [RecommendationController::class, 'index'])->name('index');
        Route::get('/for-user/{user}', [RecommendationController::class, 'forUser'])->name('for-user');
        Route::get('/for-property/{property}', [RecommendationController::class, 'forProperty'])->name('for-property');
        Route::get('/similar/{property}', [RecommendationController::class, 'similarProperties'])->name('similar');
        Route::get('/preferences', [RecommendationController::class, 'editPreferences'])->name('preferences');
        Route::post('/preferences', [RecommendationController::class, 'updatePreferences'])->name('preferences.update');
    });
    
    // Messagerie - Accessible à tous les utilisateurs authentifiés
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/agents', [PropertyMessageController::class, 'showAgents'])->name('agents');
        Route::get('/properties', [PropertyMessageController::class, 'propertyConversations'])->name('properties');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Routes pour les visites de propriétés
    |--------------------------------------------------------------------------
    */
    
    // Visites pour les clients - Nécessite permission
    Route::middleware(['permission:schedule-visits'])->group(function () {
        Route::get('/properties/{property}/visits/create', [PropertyVisitController::class, 'create'])->name('properties.visits.create');
        Route::post('/properties/{property}/visits', [PropertyVisitController::class, 'store'])->name('properties.visits.store');
        Route::get('/visits', [PropertyVisitController::class, 'index'])->name('visits.index');
        Route::get('/visits/{visit}', [PropertyVisitController::class, 'show'])->name('visits.show');
        Route::get('/visits/{visit}/cancel', [PropertyVisitController::class, 'cancelForm'])->name('visits.cancel.form');
        Route::post('/visits/{visit}/cancel', [PropertyVisitController::class, 'cancel'])->name('visits.cancel');
    });
    
    // Visites pour les agents - Nécessite rôle agent ou admin
    Route::middleware(['permission:manage-properties'])->prefix('agent')->name('agent.')->group(function () {
        Route::get('/visits', [PropertyVisitController::class, 'agentIndex'])->name('visits.index');
        Route::get('/visits/{visit}', [PropertyVisitController::class, 'show'])->name('visits.show');
        Route::post('/visits/{visit}/confirm', [PropertyVisitController::class, 'confirm'])->name('visits.confirm');
        Route::post('/visits/{visit}/complete', [PropertyVisitController::class, 'complete'])->name('visits.complete');
        Route::get('/visits/{visit}/cancel', [PropertyVisitController::class, 'cancelForm'])->name('visits.cancel.form');
        Route::post('/visits/{visit}/cancel', [PropertyVisitController::class, 'cancel'])->name('visits.cancel');
    });
    
    // Visites pour les administrateurs - Nécessite rôle admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::post('/visits/{visit}/reassign', [PropertyVisitController::class, 'reassign'])->name('visits.reassign');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Routes pour les enchères
    |--------------------------------------------------------------------------
    */
    
    // Consultation des enchères - Accessible à tous les utilisateurs authentifiés
    Route::prefix('auctions')->name('auctions.')->group(function () {
        Route::get('/', [AuctionController::class, 'index'])->name('index');
        Route::get('/{auction}', [AuctionController::class, 'show'])->name('show');
        Route::get('/history', [AuctionController::class, 'history'])->name('history');
        
        // Participation aux enchères - Nécessite permission
        Route::middleware(['permission:participate-auctions'])->group(function () {
            Route::post('/{auction}/bid', [AuctionController::class, 'placeBid'])->name('bid');
        });
        
        // Gestion des enchères - Nécessite permission
        Route::middleware(['permission:manage-auctions'])->group(function () {
            Route::get('/create', [AuctionController::class, 'create'])->name('create');
            Route::post('/', [AuctionController::class, 'store'])->name('store');
            Route::get('/{auction}/edit', [AuctionController::class, 'edit'])->name('edit');
            Route::put('/{auction}', [AuctionController::class, 'update'])->name('update');
            Route::delete('/{auction}', [AuctionController::class, 'destroy'])->name('destroy');
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Routes pour les agents immobiliers
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['role:agent,agency_admin,admin'])->group(function () {
        
        // Gestion des propriétés - Nécessite permission
        Route::middleware(['permission:manage-properties'])->prefix('properties')->name('properties.')->group(function () {
            Route::get('/create', [PropertyController::class, 'create'])->name('create');
            Route::post('/', [PropertyController::class, 'store'])->name('store');
            Route::get('/{property}/edit', [PropertyController::class, 'edit'])->name('edit');
            Route::get('/{property}/media', [PropertyController::class, 'editMedia'])->name('media');
            Route::put('/{property}', [PropertyController::class, 'update'])->name('update');
            Route::delete('/{property}', [PropertyController::class, 'destroy'])->name('destroy');
            Route::get('/{property}/visits', [PropertyController::class, 'visits'])->name('visits');
            
            // Visites virtuelles - Nécessite permission
            Route::middleware(['permission:manage-virtual-tours'])->group(function () {
                Route::get('/{property}/virtual-tour/edit', [VirtualTourController::class, 'edit'])->name('virtual-tour.edit');
                Route::put('/{property}/virtual-tour', [VirtualTourController::class, 'update'])->name('virtual-tour.update');
                Route::post('/{property}/virtual-tour/basic', [VirtualTourController::class, 'createBasicTour'])->name('virtual-tour.basic');
                Route::delete('/{property}/virtual-tour', [VirtualTourController::class, 'destroy'])->name('virtual-tour.destroy');
            });
        });
        
        // Gestion des leads - Nécessite permission
        Route::middleware(['permission:manage-leads'])->prefix('leads')->name('leads.')->group(function () {
            Route::get('/', [LeadController::class, 'index'])->name('index');
            Route::get('/create', [LeadController::class, 'create'])->name('create');
            Route::post('/', [LeadController::class, 'store'])->name('store');
            Route::get('/{lead}', [LeadController::class, 'show'])->name('show');
            Route::get('/{lead}/edit', [LeadController::class, 'edit'])->name('edit');
            Route::put('/{lead}', [LeadController::class, 'update'])->name('update');
            Route::delete('/{lead}', [LeadController::class, 'destroy'])->name('destroy');
            Route::post('/{lead}/activities', [LeadController::class, 'addActivity'])->name('activities.add');
            Route::post('/activities/{activity}/complete', [LeadController::class, 'completeActivity'])->name('activities.complete');
            
            // Import/Export - Nécessite permission supplémentaire
            Route::middleware(['permission:import-export-leads'])->group(function () {
                Route::get('/import', [LeadController::class, 'importForm'])->name('import.form');
                Route::post('/import', [LeadController::class, 'import'])->name('import');
                Route::get('/export', [LeadController::class, 'export'])->name('export');
            });
            
            // Transfert de leads - Nécessite permission supplémentaire
            Route::middleware(['permission:transfer-leads'])->group(function () {
                Route::post('/{lead}/transfer', [LeadController::class, 'transferLead'])->name('transfer');
            });
        });
        
        // Marketing - Nécessite permission
        Route::middleware(['permission:manage-marketing'])->prefix('marketing')->name('marketing.')->group(function () {
            Route::get('/', [MarketingController::class, 'index'])->name('index');
            Route::get('/create', [MarketingController::class, 'create'])->name('create');
            Route::post('/', [MarketingController::class, 'store'])->name('store');
            Route::get('/{marketing}', [MarketingController::class, 'show'])->name('show');
            Route::get('/{marketing}/edit', [MarketingController::class, 'edit'])->name('edit');
            Route::put('/{marketing}', [MarketingController::class, 'update'])->name('update');
            Route::delete('/{marketing}', [MarketingController::class, 'destroy'])->name('destroy');
            Route::post('/{campaign}/results', [MarketingController::class, 'addResult'])->name('results.add');
            Route::delete('/results/{result}', [MarketingController::class, 'deleteResult'])->name('results.delete');
        });
        
        // Rapports - Nécessite permission
        Route::middleware(['permission:view-reports'])->prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/generate', [ReportController::class, 'generate'])->name('generate');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Routes pour les administrateurs d'agence
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['role:agency_admin,admin'])->group(function () {
        // Tableau de bord d'agence
        Route::get('/agency/dashboard', [AgencyDashboardController::class, 'index'])->name('agency.dashboard');
        Route::post('/agency/switch', [AgencyDashboardController::class, 'switchAgency'])->name('agency.switch');
        
        // Gestion des agents - Nécessite permission
        Route::middleware(['permission:manage-agents'])->prefix('agents')->name('agents.')->group(function () {
            Route::get('/', [AgentController::class, 'index'])->name('index');
            Route::get('/create', [AgentController::class, 'create'])->name('create');
            Route::post('/', [AgentController::class, 'store'])->name('store');
            Route::get('/{agent}', [AgentController::class, 'show'])->name('show');
            Route::get('/{agent}/edit', [AgentController::class, 'edit'])->name('edit');
            Route::put('/{agent}', [AgentController::class, 'update'])->name('update');
            Route::delete('/{agent}', [AgentController::class, 'destroy'])->name('destroy');
            Route::post('/invite', [AgentController::class, 'invite'])->name('invite');
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Routes pour les administrateurs système
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['role:admin'])->group(function () {
        // Gestion des rôles et permissions
        Route::middleware(['permission:manage-roles'])->resource('roles', RoleController::class);
        
        // Gestion des agences
        Route::middleware(['permission:manage-agencies'])->resource('agencies', AgencyController::class);
        
        // Gestion des entreprises
        Route::middleware(['permission:manage-companies'])->resource('companies', CompanyController::class);
        
        // Gestion des modules (extensions)
        Route::middleware(['permission:manage-modules'])->prefix('modules')->name('modules.')->group(function () {
            Route::get('/', [ModuleController::class, 'index'])->name('index');
            Route::get('/create', [ModuleController::class, 'create'])->name('create');
            Route::post('/', [ModuleController::class, 'store'])->name('store');
            Route::get('/{module}', [ModuleController::class, 'show'])->name('show');
            Route::get('/{module}/edit', [ModuleController::class, 'edit'])->name('edit');
            Route::put('/{module}', [ModuleController::class, 'update'])->name('update');
            Route::delete('/{module}', [ModuleController::class, 'destroy'])->name('destroy');
            Route::post('/{module}/assign', [ModuleController::class, 'assignToCompany'])->name('assign');
            Route::delete('/{module}/companies/{company}', [ModuleController::class, 'removeFromCompany'])->name('remove');
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Routes pour la gestion de projets (entreprises)
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['role:company_admin,company_user,admin'])->group(function () {
        // Gestion des équipes
        Route::middleware(['permission:manage-teams'])->prefix('companies/{company}')->group(function () {
            Route::resource('teams', TeamController::class);
            Route::post('teams/{team}/invite', [TeamController::class, 'invite'])->name('teams.invite');
            Route::get('/teams/{team}/members', [TeamMemberController::class, 'index'])->name('teams.members.index');
            Route::get('/teams/{team}/members/create', [TeamMemberController::class, 'create'])->name('teams.members.create');
            Route::post('/teams/{team}/members', [TeamMemberController::class, 'store'])->name('teams.members.store');
            Route::get('/teams/{team}/members/{member}/edit', [TeamMemberController::class, 'edit'])->name('teams.members.edit');
            Route::put('/teams/{team}/members/{member}', [TeamMemberController::class, 'update'])->name('teams.members.update');
            Route::delete('/teams/{team}/members/{member}', [TeamMemberController::class, 'destroy'])->name('teams.members.destroy');
            Route::post('/teams/{team}/invite', [TeamMemberController::class, 'invite'])->name('teams.members.invite');
        });
        
        // Gestion des projets
        Route::middleware(['permission:manage-projects'])->prefix('companies/{company}')->group(function () {
            Route::resource('projects', ProjectController::class);
            Route::get('projects/{project}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');
            Route::get('projects/{project}/gantt', [ProjectController::class, 'gantt'])->name('projects.gantt');
            
            // Gestion des tâches
            Route::prefix('projects/{project}')->group(function () {
                Route::resource('tasks', TaskController::class);
                Route::post('tasks/{task}/subtasks', [TaskController::class, 'addSubtask'])->name('tasks.subtasks.add');
                Route::patch('tasks/{task}/subtasks/{subtask}', [TaskController::class, 'toggleSubtask'])->name('tasks.subtasks.toggle');
                Route::post('tasks/{task}/comments', [TaskController::class, 'addComment'])->name('tasks.comments.add');
                Route::post('tasks/{task}/attachments', [TaskController::class, 'uploadAttachment'])->name('tasks.attachments.upload');
                Route::delete('tasks/{task}/attachments/{attachment}', [TaskController::class, 'deleteAttachment'])->name('tasks.attachments.delete');
                Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status.update');
            });
        });
    });
});

















require __DIR__.'/admin.php';