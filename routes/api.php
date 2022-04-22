<?php

use App\Http\Controllers\TargetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/targets' , [TargetController::class , 'store']);