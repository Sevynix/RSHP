<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RshpController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KategoriKlinisController;
use App\Http\Controllers\Admin\KodeTindakanTerapiController;
use App\Http\Controllers\Admin\JenisHewanController;
use App\Http\Controllers\Admin\RasHewanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleUserController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\PemilikController;

Route::get('/', [RshpController::class, 'home']);
Route::get('/layanan', [RshpController::class, 'layanan']);
Route::get('/visimisi', [RshpController::class, 'visimisi']);
Route::get('/struktur', [RshpController::class, 'struktur']);

// Laravel UI Authentication Routes
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    
    Route::get('/kategoriklinis', [KategoriKlinisController::class, 'index'])->name('kategoriklinis.index');
    Route::get('/kategoriklinis/create', [KategoriKlinisController::class, 'create'])->name('kategoriklinis.create');
    Route::post('/kategoriklinis', [KategoriKlinisController::class, 'store'])->name('kategoriklinis.store');
    Route::get('/kategoriklinis/{id}/edit', [KategoriKlinisController::class, 'edit'])->name('kategoriklinis.edit');
    Route::put('/kategoriklinis/{id}', [KategoriKlinisController::class, 'update'])->name('kategoriklinis.update');
    Route::delete('/kategoriklinis/{id}', [KategoriKlinisController::class, 'destroy'])->name('kategoriklinis.destroy');
    
    Route::get('/kodetindakanterapi', [KodeTindakanTerapiController::class, 'index'])->name('kodetindakanterapi.index');
    Route::get('/kodetindakanterapi/create', [KodeTindakanTerapiController::class, 'create'])->name('kodetindakanterapi.create');
    Route::post('/kodetindakanterapi', [KodeTindakanTerapiController::class, 'store'])->name('kodetindakanterapi.store');
    Route::get('/kodetindakanterapi/{id}/edit', [KodeTindakanTerapiController::class, 'edit'])->name('kodetindakanterapi.edit');
    Route::put('/kodetindakanterapi/{id}', [KodeTindakanTerapiController::class, 'update'])->name('kodetindakanterapi.update');
    Route::delete('/kodetindakanterapi/{id}', [KodeTindakanTerapiController::class, 'destroy'])->name('kodetindakanterapi.destroy');
    
    Route::get('/jenishewan', [JenisHewanController::class, 'index'])->name('jenishewan.index');
    Route::get('/jenishewan/create', [JenisHewanController::class, 'create'])->name('jenishewan.create');
    Route::post('/jenishewan', [JenisHewanController::class, 'store'])->name('jenishewan.store');
    Route::get('/jenishewan/{id}/edit', [JenisHewanController::class, 'edit'])->name('jenishewan.edit');
    Route::put('/jenishewan/{id}', [JenisHewanController::class, 'update'])->name('jenishewan.update');
    Route::delete('/jenishewan/{id}', [JenisHewanController::class, 'destroy'])->name('jenishewan.destroy');
    
    Route::get('/rashewan', [RasHewanController::class, 'index'])->name('rashewan.index');
    Route::get('/rashewan/create', [RasHewanController::class, 'create'])->name('rashewan.create');
    Route::post('/rashewan', [RasHewanController::class, 'store'])->name('rashewan.store');
    Route::get('/rashewan/{id}/edit', [RasHewanController::class, 'edit'])->name('rashewan.edit');
    Route::put('/rashewan/{id}', [RasHewanController::class, 'update'])->name('rashewan.update');
    Route::delete('/rashewan/{id}', [RasHewanController::class, 'destroy'])->name('rashewan.destroy');
    
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPasswordConfirm'])->name('users.reset-password.confirm');
    
    Route::get('/role-user', [RoleUserController::class, 'index'])->name('role-user.index');
    Route::get('/role-user/{iduser}/assign', [RoleUserController::class, 'assign'])->name('role-user.assign');
    Route::post('/role-user/{iduser}', [RoleUserController::class, 'update'])->name('role-user.update');
    
    Route::get('/pet', [PetController::class, 'index'])->name('pet.index');
    Route::get('/pet/create', [PetController::class, 'create'])->name('pet.create');
    Route::post('/pet', [PetController::class, 'store'])->name('pet.store');
    Route::get('/pet/{id}/edit', [PetController::class, 'edit'])->name('pet.edit');
    Route::put('/pet/{id}', [PetController::class, 'update'])->name('pet.update');
    Route::delete('/pet/{id}', [PetController::class, 'destroy'])->name('pet.destroy');

    Route::get('/pemilik', [PemilikController::class, 'index'])->name('pemilik.index');
    Route::get('/pemilik/create', [PemilikController::class, 'create'])->name('pemilik.create');
    Route::post('/pemilik/store-new', [PemilikController::class, 'storeNew'])->name('pemilik.store-new');
    Route::post('/pemilik/store-existing', [PemilikController::class, 'storeExisting'])->name('pemilik.store-existing');
    Route::delete('/pemilik/{id}', [PemilikController::class, 'destroy'])->name('pemilik.destroy');
});
