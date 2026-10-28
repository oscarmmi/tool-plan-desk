<?php

use App\Http\Controllers\PlanJobController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlanJobController::class, 'index'])->name('home');
Route::post('/plans', [PlanJobController::class, 'store'])->name('plans.store');
