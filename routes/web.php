<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\MoveController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ポケモン一覧ページを表示するルート
Route::resource('pokemons', PokemonController::class)->only(['index', 'show']);
//技一覧ページを表示する
Route::resource('moves', MoveController::class)->only(['index', 'show']);



require __DIR__.'/auth.php';
