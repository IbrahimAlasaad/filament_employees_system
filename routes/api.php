<?php

use App\Http\Resources\EmployeeReource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Employee;
 

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/employees' , function (Request $request) {
    $employees=Employee::orderBy('lname','asc')->get();
    return EmployeeReource::collection($employees);
});
