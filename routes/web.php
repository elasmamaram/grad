<?php

use App\Http\Controllers\ExperimentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExperimentController::class, 'landing'])->name('experiment.landing');
Route::post('/start', [ExperimentController::class, 'start'])->name('experiment.start');
Route::get('/experiment/{participant}/{step}', [ExperimentController::class, 'show'])->whereNumber('step')->name('experiment.show');
Route::post('/experiment/{participant}/{step}', [ExperimentController::class, 'store'])->whereNumber('step')->name('experiment.store');
Route::get('/complete/{participant}', [ExperimentController::class, 'complete'])->name('experiment.complete');
