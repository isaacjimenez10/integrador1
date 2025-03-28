<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\ConfiguracionController;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// routes/web.php

use App\Http\Controllers\SensorDataController;

Route::prefix('sensordata')->group(function () {
    Route::get('/', [SensorDataController::class, 'index'])->name('sensor-data.index'); // GET /sensordata (vista)
    Route::post('/', [SensorDataController::class, 'store'])->name('sensor-data.store'); // POST /sensordata (formulario)
    Route::get('/data', [SensorDataController::class, 'getData'])->name('sensor-data.data'); // GET /sensordata/data (para gráficas)
});

Route::get('/sensor-data', [SensorDataController::class, 'index'])
    ->name('sensor-data.index');
Route::post('/sensor-data', [SensorDataController::class, 'store'])
    ->name('sensor-data.store');
    
// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});
Route::get('/mision', function () {
    return view('mision');
});
Route::get('/vision', function () {
    return view('vision');
});
Route::get('/objetivos', function () {
    return view('objetivos');
});
Route::get('/nosotros', function () {
    return view('nosotros');
});




Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::delete('/users/{user}', [AdminDashboardController::class, 'destroy'])->name('users.destroy');
Route::get('users/{id}/edit', [AdminDashboardController::class, 'edit'])->name('user.edit'); 
Route::put('users/{id}', [AdminDashboardController::class, 'update'])->name('users.update');
Route::patch('users/{id}/toggle-block', [AdminDashboardController::class, 'toggleBlock'])->name('users.toggleBlock');
Route::get('/check-user-status', [AuthenticatedSessionController::class, 'checkUserStatus'])->name('check.user.status');

// Importar desde Excel
Route::post('/admin/import', function (Request $request) {
    try {
        Excel::import(new UsersImport, $request->file('file'));
        return redirect()->route('admin.dashboard')->with('success', 'Usuarios importados correctamente');
    } catch (\Exception $e) {
        return redirect()->route('admin.dashboard')->with('error', 'Error al importar usuarios: ' . $e->getMessage());
    }
})->name('admin.import');

Route::get('users/export', [AdminDashboardController::class, 'export'])->name('users.export');


// Logout
Route::post('/admin/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/login');
})->name('admin.logout');

// Rutas CRUD completas para Sensor
Route::get('sensores', [SensorController::class, 'index'])->name('sensores.index');
Route::get('sensores/create', [SensorController::class, 'create'])->name('sensores.create');
Route::post('sensores', [SensorController::class, 'store'])->name('sensores.store');
Route::get('sensores/{id}', [SensorController::class, 'show'])->name('sensores.show'); 
Route::get('sensores/{id}/edit', [SensorController::class, 'edit'])->name('sensores.edit'); 
Route::put('sensores/{id}', [SensorController::class, 'update'])->name('sensores.update'); 
Route::delete('sensores/{id}', [SensorController::class, 'destroy'])->name('sensores.destroy');
Route::get('sensores/export', [SensorController::class, 'export'])->name('sensores.export'); 

// Rutas CRUD completas para Lectura
Route::get('lecturas', [LecturaController::class, 'index'])->name('lecturas.index');
Route::get('lecturas/create', [LecturaController::class, 'create'])->name('lecturas.create');
Route::post('lecturas', [LecturaController::class, 'store'])->name('lecturas.store');
Route::get('lecturas/{id}', [LecturaController::class, 'show'])->name('lecturas.show'); 
Route::get('lecturas/{id}/edit', [LecturaController::class, 'edit'])->name('lecturas.edit'); 
Route::put('lecturas/{id}', [LecturaController::class, 'update'])->name('lecturas.update'); 
Route::delete('lecturas/{id}', [LecturaController::class, 'destroy'])->name('lecturas.destroy'); 
Route::get('lecturas/export', [LecturaController::class, 'export'])->name('lecturas.export');

// Rutas CRUD completas para Configuración
Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
Route::get('configuracion/create', [ConfiguracionController::class, 'create'])->name('configuracion.create');
Route::post('configuracion', [ConfiguracionController::class, 'store'])->name('configuracion.store');
Route::get('configuracion/{id}', [ConfiguracionController::class, 'show'])->name('configuracion.show');
Route::get('configuracion/{id}/edit', [ConfiguracionController::class, 'edit'])->name('configuracion.edit'); 
Route::put('configuracion/{id}', [ConfiguracionController::class, 'update'])->name('configuracion.update'); 
Route::delete('configuracion/{id}', [ConfiguracionController::class, 'destroy'])->name('configuracion.destroy');
Route::get('/configuracion/export', [ConfiguracionController::class, 'export'])->name('configuracion.export');

require __DIR__.'/auth.php';
