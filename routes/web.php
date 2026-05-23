<?php


use App\Http\Controllers\EventRequestsController;

use App\Http\Controllers\HelpFormatController;
use App\Http\Controllers\IncomingReportsController;
use App\Http\Controllers\IssueCategoryController;
use App\Http\Controllers\MunicipalityController;

use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ResultReportsController;

use App\Http\Controllers\UserController;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::any('/register-webhook', [\App\Http\Controllers\SystemController::class, "registerWebhooks"]);
Route::post('/webhook', [\App\Http\Controllers\SystemController::class, "handler"]);
Route::get("/blocked", [\App\Http\Controllers\SystemController::class, "blockedPage"])
    ->name("blocked");

Route::get("/", function (Request $request){
    $agent = new Agent();

    if ($agent->isMobile()) {
        return redirect('/app/');
    }

    return view("welcome");
});






Route::get("/app/{any?}", [\App\Http\Controllers\SystemController::class, "homePage"])
    ->where('any', '.*');

