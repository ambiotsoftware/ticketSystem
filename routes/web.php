<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketTimeController;
use App\Http\Controllers\ClientPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\EmailNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página de inicio
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Grupo principal con autenticación y verificación
Route::middleware(['auth', 'verified'])->group(function () {



//    // --- Plan del cliente ---
//Route::get('/my-plan', [ClientPlanController::class, 'myPlan'])
//    ->name('client.plan')
//    ->middleware('role:client,admin');
//
//// --- Planes del administrador ---
//Route::get('/my-plan-admin', [ClientPlanController::class, 'myPlanAdmin'])
//    ->name('admin.plan')
//    ->middleware('role:admin');


    // --- Dashboard principal ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Perfil del usuario ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//    // --- Plan del cliente o administrador ---
//    Route::get('/my-plan', [ClientPlanController::class, 'myPlan'])
//        ->name('client.plan')
//        ->middleware('role:admin,client');

    Route::get('my-plans', [ClientPlanController::class, 'myPlans'])
        ->name('my-plans.index')->middleware('role:client');

    // --- Tickets ---
    Route::resource('tickets', TicketController::class);

    // --- Técnicos y administradores ---
    Route::middleware(['role:technician,admin'])->group(function () {
        Route::post('/tickets/{ticket}/assign-to-me', [TicketController::class, 'assignToMe'])->name('tickets.assign-to-me');
        Route::post('/tickets/{ticket}/time-control', [TicketTimeController::class, 'timeControl'])->name('tickets.time-control');
        Route::post('/tickets/{ticket}/reassign', [TicketController::class, 'reassign'])->name('tickets.reassign');
    });

    // --- Administradores ---
    Route::middleware(['role:admin'])->group(function () {

//        // --- Gestión de Planes de Clientes (CRUD completo) ---
//        Route::get('/clientplans', [ClientPlanController::class, 'index'])->name('clientplans.index');
//        Route::get('/clientplans/create', [ClientPlanController::class, 'create'])->name('clientplans.create');
//        Route::post('/clientplans', [ClientPlanController::class, 'store'])->name('clientplans.store');
//        Route::get('/clientplans/{clientplan}', [ClientPlanController::class, 'show'])->name('clientplans.show');
//        Route::get('/clientplans/{clientplan}/edit', [ClientPlanController::class, 'edit'])->name('clientplans.edit');
//        Route::put('/clientplans/{clientplan}', [ClientPlanController::class, 'update'])->name('clientplans.update');
//        Route::delete('/clientplans/{clientplan}', [ClientPlanController::class, 'destroy'])->name('clientplans.destroy');
//
//        // --- Planes ---
        Route::resource('plans', PlanController::class)
            ->except('show')
            ->names('admin.plans');
        // --- Asignar plan ---
        Route::resource('client-plans', ClientPlanController::class)
            ->parameters(['client-plans' => 'clientPlan'])
            ->except('show')
            ->names('admin.client-plans');
//
//        // --- Asignar planes a clientes ---
//        Route::get('clients/{client}/plans', [ClientPlanController::class, 'edit'])->name('clients.plans.edit');
//        Route::put('clients/{client}/plans', [ClientPlanController::class, 'update'])->name('clients.plans.update');

        // --- Categorías ---
        Route::resource('categories', CategoryController::class);

        // --- Usuarios ---
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('/users/{id}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::get('/users/manage', [UserController::class, 'manage'])->name('users.manage');

        // --- Notificaciones ---
        Route::get('/admin/notifications', [EmailNotificationController::class, 'index'])->name('admin.notifications.index');
        Route::get('/admin/notifications/{notification}', [EmailNotificationController::class, 'show'])->name('admin.notifications.show');
        Route::post('/admin/notifications/mark-sent', [EmailNotificationController::class, 'markAsSent'])->name('admin.notifications.mark-sent');

        // --- Reporte PDF de tickets ---
        Route::get('/tickets/{id}/reporte-pdf', [TicketController::class, 'reportePDF'])->name('tickets.reportePDF');

        // --- Asignación rápida de tickets ---
        Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    });
});

require __DIR__.'/auth.php';
