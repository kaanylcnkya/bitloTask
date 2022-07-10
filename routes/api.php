<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [CompanyController::class, 'register']);
Route::post('/login', [CompanyController::class, 'login'])->name('login');
Route::post('/company_package', [CompanyController::class, 'company_package']);

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::get('/company_package/{id}', [CompanyController::class, 'check_company_package']);
});