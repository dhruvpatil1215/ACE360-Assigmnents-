<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('employees.index');
});

// Company CRUD
Route::resource('companies', CompanyController::class);

// Employee CRUD
Route::resource('employees', EmployeeController::class);

// DataTables API for employees
Route::get('/api/employees', [EmployeeController::class, 'getData'])->name('employees.data');

// Location API (proxies to Universal Tutorial)
Route::get('/api/countries', [LocationController::class, 'countries'])->name('api.countries');
Route::get('/api/states/{country}', [LocationController::class, 'states'])->name('api.states');
Route::get('/api/cities/{state}', [LocationController::class, 'cities'])->name('api.cities');
