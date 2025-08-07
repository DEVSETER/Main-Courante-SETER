<?php

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\EntiteController;
use App\Http\Controllers\ImpactController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\MockSSOController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ListeDiffusionController;
use App\Http\Controllers\NatureEvenementController;


Route::get('/', function () {
    return redirect()->route('auth.login');
});

Route::get('/database-unavailable', function () {
    return view('errors.database-unavailable');
})->name('database.unavailable');

Route::get('/admin/system/status', function () {
    try {
        DB::connection()->getPdo();
        DB::connection()->select('SELECT 1');

        return response()->json([
            'database' => 'accessible',
            'timestamp' => now(),
            'status' => 'OK'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'database' => 'inaccessible',
            'error' => $e->getMessage(),
            'timestamp' => now(),
            'status' => 'ERROR'
        ], 503);
    }
})->name('admin.system.status');


Route::prefix('connexion')->group(function () {
    Route::get('/', [AuthenticationController::class, 'showLogin'])
         ->name('auth.login');

    Route::post('/sso', [AuthenticationController::class, 'initiateSSO'])
         ->name('auth.sso.initiate');

    Route::get('/sso/callback', [AuthenticationController::class, 'handleWallixCallback'])
         ->name('auth.sso.callback');

    Route::post('/email', [AuthenticationController::class, 'initiateEmail'])
         ->name('auth.email.initiate');

    Route::post('/email/resend', [AuthenticationController::class, 'resendEmailToken'])
         ->name('auth.email.resend');

    Route::get('/email/verify/{token}', [AuthenticationController::class, 'verifyEmailToken'])
         ->name('auth.email.verify');
});


Route::get('/auth/wallix/callback', [AuthenticationController::class, 'wallixCallback'])->name('auth.wallix.callback');
// Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {

// Route de déconnexion
Route::post('/deconnexion', [AuthenticationController::class, 'logout'])
     ->name('auth.logout');


Route::get('/entites', [EntiteController::class, 'create'])->middleware('permission:Créer entité')->name('entite.creation');
Route::post('/entites/store', [EntiteController::class, 'store'])->middleware('permission:Créer entité')->name('entites.store');
Route::get('/entites/index', [EntiteController::class, 'index'])->middleware('permission:Consulter liste entités')->name('entites.index');
Route::put('/entites/{id}', [EntiteController::class, 'update'])->middleware('permission:Modifier entité')->name('entites.update');
Route::get('/entites/{id}/edit', [EntiteController::class, 'edit'])->middleware('permission:Modifier entité')->name('entites.edit');
Route::delete('/entites/{id}', [EntiteController::class, 'destroy'])->middleware('permission:Supprimer entité')->name('entites.destroy');

// Routes  permissions
Route::get('/permissions', [PermissionController::class, 'create'])->middleware('permission:Créer privilège')->name('permission.creation');
Route::post('/permissions/store', [PermissionController::class, 'store'])->middleware('permission:Créer privilège')->name('permissions.store');
Route::get('/permissions/index', [PermissionController::class, 'index'])->middleware('permission:Consulter liste privilège')->name('permissions.index');
Route::put('/permissions/{id}', [PermissionController::class, 'update'])->middleware('permission:Modifier privilège')->name('permissions.update');
Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->middleware('permission:Modifier privilège')->name('permissions.edit');
Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->middleware('permission:Supprimer privilège')->name('permissions.destroy');


// Routes  utilisateurs

    Route::get('/users', [UserController::class, 'create'])->middleware('permission:Consulter liste utilisateurs')->name('users.creation');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:Créer utilisateur')->name('users.store');
    Route::get('/users/index', [UserController::class, 'index'])->middleware('permission:Consulter liste utilisateurs')->name('users.index');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->middleware('permission:Modifier utilisateur')->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('permission:Modifier utilisateur')->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('permission:Supprimer utilisateur')->name('users.destroy');

//Gestion des rôles

Route::get('/roles', [RoleController::class, 'create'])->middleware('permission:Créer rôle')->name('roles.creation');
Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->middleware('permission:Modifier rôle')->name('roles.edit');
Route::get('/roles/index', [RoleController::class, 'index'])->middleware('permission:Consulter liste rôles')->name('roles.index');
Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:Créer rôle')->name('roles.store');
Route::put('/roles/{id}', [RoleController::class, 'update'])->middleware('permission:Modifier rôle')->name('roles.update');
Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware('permission:Supprimer rôle')->name('roles.destroy');

//Liste de diffusion
Route::get('/liste_diffusions', [ListeDiffusionController::class, 'index'])->middleware('permission:Consulter liste diffusion')->name('liste_diffusions.index');
Route::get('/liste_diffusions/create', [ListeDiffusionController::class, 'create'])->middleware('permission:Créer liste diffusion')->name('liste_diffusions.create');
Route::post('/liste_diffusions/store', [ListeDiffusionController::class, 'store'])->middleware('permission:Créer liste diffusion')->name('liste_diffusions.store');
Route::get('/liste_diffusions/{id}/edit', [ListeDiffusionController::class, 'edit'])->middleware('permission:Modifier liste diffusion')->name('liste_diffusions.edit');
Route::put('/liste_diffusions/{id}', [ListeDiffusionController::class, 'update'])->middleware('permission:Modifier liste diffusion')->name('liste_diffusions.update');
Route::delete('/liste_diffusions/{id}', [ListeDiffusionController::class, 'destroy'])->middleware('permission:Supprimer liste diffusion')->name('liste_diffusions.destroy');



//Route pour evenements



Route::post('/evenements/{evenement}/diffuser', [EvenementController::class, 'diffuserEvenement'])->name('evenements.diffuser');

Route::get('/evenements', [EvenementController::class, 'index'])->middleware('permission:Consulter liste événements')->name('evenements.index');
Route::post('/evenements', [EvenementController::class, 'store'])->middleware('permission:Créer événement')->name('evenements.store');
Route::get('/evenements/create', [EvenementController::class, 'create'])->middleware('permission:Créer événement')->name('evenements.create');
Route::get('/evenements/{id}/edit', [EvenementController::class, 'edit'])->middleware('permission:Modifier événement')->name('evenements.edit');
Route::put('/evenements/{id}', [EvenementController::class, 'update'])->middleware('permission:Modifier événement')->name('evenements.update');
Route::delete('/evenements/{evenement}', [EvenementController::class, 'destroy'])->middleware('permission:Supprimer événement')->name('evenements.destroy');

Route::get('/evenements/json', [EvenementController::class, 'json'])->name('evenements.json');
Route::post('/evenements/{id}/update-field', [EvenementController::class, 'updateField'])->middleware('auth');
//Routes Nature Evenement


Route::get('/nature_evenements', [NatureEvenementController::class, 'index'])->middleware('permission:Consulter liste nature événements')->name('nature_evenements.index');
Route::get('/nature_evenements/create', [NatureEvenementController::class, 'create'])->middleware('permission:Créer nature événements')->name('nature_evenements.create');
Route::post('/nature_evenements/store', [NatureEvenementController::class, 'store'])->middleware('permission:Créer nature événements')->name('nature_evenements.store');
Route::get('/nature_evenements/{id}/edit', [NatureEvenementController::class, 'edit'])->middleware('permission:Modifier nature événements')->name('nature_evenements.edit');
Route::put('/nature_evenements/{id}', [NatureEvenementController::class, 'update'])->middleware('permission:Modifier nature événements')->name('nature_evenements.update');
Route::delete('/nature_evenements/{id}', [NatureEvenementController::class, 'destroy'])->middleware('permission:Supprimer nature événements')->name('nature_evenements.destroy');

//Routes pour les lieux
Route::get('/locations', [LocationController::class, 'index'])->middleware('permission:Consulter liste lieux')->name('locations.index');
Route::get('/locations/create', [LocationController::class, 'create'])->middleware('permission:Créer lieu')->name('locations.create');
Route::post('/locations/store', [LocationController::class, 'store'])->middleware('permission:Créer lieu')->name('locations.store');
Route::get('/locations/{id}/edit', [LocationController::class, 'edit'])->middleware('permission:Modifier lieu')->name('locations.edit');
Route::put('/locations/{id}', [LocationController::class, 'update'])->middleware('permission:Modifier lieu')->name('locations.update');
Route::delete('/locations/{id}', [LocationController::class, 'destroy'])->middleware('permission:Supprimer lieu')->name('locations.destroy');


//Routes pour les impacts
Route::get('/impacts', [ImpactController::class, 'index'])->middleware('permission:Consulter liste impacts')->name('impacts.index');
Route::get('/impacts/create', [ImpactController::class, 'create'])->middleware('permission:Créer impact')->name('impacts.create');
Route::post('/impacts/store', [ImpactController::class, 'store'])->middleware('permission:Créer impact')->name('impacts.store');
Route::get('/impacts/{id}/edit', [ImpactController::class, 'edit'])->middleware('permission:Modifier impact')->name('impacts.edit');
Route::put('/impacts/{id}', [ImpactController::class, 'update'])->middleware('permission:Modifier impact')->name('impacts.update');
Route::delete('/impacts/{id}', [ImpactController::class, 'destroy'])->middleware('permission:Supprimer impact')->name('impacts.destroy');

// routes pour  les rapport
Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
Route::get('/rapport/export/{type}/{format}', [RapportController::class, 'export'])->name('rapports.export');

Route::get('/rapports/export/date/{date}/{format}', [RapportController::class, 'exportByDate'])->name('rapports.export.date');
Route::get('/rapports/export/month/{month}/{format}', [RapportController::class, 'exportByMonth'])->name('rapports.export.month');


// Routes pour les actions
Route::put('/actions/{action}', [ActionController::class, 'update'])->middleware('permission:Modifier action')->name('actions.update');
Route::delete('/actions/{action}', [ActionController::class, 'destroy'])->middleware('permission:Supprimer action')->name('actions.destroy');


// Routes pour les commentaires
Route::put('/commentaires/{commentaire}', [CommentaireController::class, 'update'])->middleware('permission:Modifier commentaire')->name('commentaires.update');
Route::delete('/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->middleware('permission:Supprimer commentaire')->name('commentaires.destroy');
// Route dashboards
Route::get('/dashboard', action: [DashboardController::class, 'index'])->middleware('permission:Consulter tableau de bord')->name('dashboard');

//route de l'annuaire


//Route des archives
Route::put('/evenements/{evenement}/unarchive', [ArchiveController::class, 'unarchive'])->middleware('permission:Modifier archive')->name('evenements.unarchive');
Route::get('/archive', action: [ArchiveController::class, 'index'])->middleware('permission:Consulter liste archive')->name('archive.index');
//   Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

});


// Routes de test SSO (uniquement en mode local)
if (app()->environment('local')) {
    Route::prefix('mock-sso')->group(function () {
        Route::get('/auth', [MockSSOController::class, 'authorize'])->name('mock.sso.auth');
        Route::post('/token', [MockSSOController::class, 'token'])->name('mock.sso.token');
        Route::get('/userinfo', [MockSSOController::class, 'userinfo'])->name('mock.sso.userinfo');
    });
}


if (app()->environment('local')) {
    Route::prefix('mock-sso')->name('mock.sso.')->group(function () {
        Route::get('/auth', function(Request $request) {
            return response()->json([
                'message' => 'Mock SSO Auth endpoint',
                'params' => $request->all()
            ]);
        })->name('auth');

        Route::post('/token', function(Request $request) {
            $code = $request->get('code');

            if ($code && str_starts_with($code, 'mock_auth_code_')) {
                return response()->json([

                    'access_token' => 'mock_access_token_' . Str::random(64),
                    'token_type' => 'Bearer',
                    'expires_in' => 3600,
                    'scope' => 'openid email profile'
                ]);
            }

            return response()->json(['error' => 'invalid_grant'], 400);
        })->name('token');
    });
}

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'app_url' => config('app.url'),
        'environment' => app()->environment()
    ]);
});



