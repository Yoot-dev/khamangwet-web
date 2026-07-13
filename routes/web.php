<?php
use App\Http\Controllers\{HomeController,EpisodeController,CharacterController,LoreController,AuthController};
use App\Http\Controllers\Admin\{DashboardController,EpisodeController as AdminEpisodeController};
use Illuminate\Support\Facades\Route;
Route::get('/', HomeController::class)->name('home');
Route::get('/episodes',[EpisodeController::class,'index'])->name('episodes.index');
Route::get('/episodes/{episode:slug}',[EpisodeController::class,'show'])->name('episodes.show');
Route::get('/characters',[CharacterController::class,'index'])->name('characters.index');
Route::get('/characters/{character:slug}',[CharacterController::class,'show'])->name('characters.show');
Route::get('/lore',[LoreController::class,'index'])->name('lore.index');
Route::get('/lore/{category:slug}/{entry:slug}',[LoreController::class,'show'])->name('lore.show');
Route::get('/about',fn()=>view('about'))->name('about');
Route::get('/support',fn()=>view('support'))->name('support');
Route::middleware('guest')->group(function(){Route::get('/login',[AuthController::class,'create'])->name('login');Route::post('/login',[AuthController::class,'store'])->middleware('throttle:6,1');});
Route::post('/logout',[AuthController::class,'destroy'])->middleware('auth')->name('logout');
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function(){ Route::get('/',DashboardController::class)->name('dashboard'); Route::resource('episodes',AdminEpisodeController::class)->except('show'); });
