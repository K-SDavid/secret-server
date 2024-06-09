<?php

use App\Http\Controllers\SecretController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/secret', [SecretController::class, 'store']);
Route::get('/secret/{secret}', [SecretController::class, 'details']);
