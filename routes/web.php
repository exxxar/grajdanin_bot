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



Route::prefix("bot-api")
    ->group(function () {

        Route::prefix('help-formats')->group(function () {
            Route::get('/', [HelpFormatController::class, 'index'])
                ->name('help-formats.index');
            Route::post('/', [HelpFormatController::class, 'store'])
                ->name('help-formats.store');
            Route::get('/{id}', [HelpFormatController::class, 'show'])
                ->name('help-formats.show');
            Route::put('/{id}', [HelpFormatController::class, 'update'])
                ->name('help-formats.update');
            Route::delete('/{id}', [HelpFormatController::class, 'destroy'])
                ->name('help-formats.destroy');
        });

        Route::prefix('municipalities')->group(function () {
            Route::get('/', [MunicipalityController::class, 'index'])
                ->name('municipalities.index');
            Route::post('/', [MunicipalityController::class, 'store'])
                ->name('municipalities.store');
            Route::get('/{id}', [MunicipalityController::class, 'show'])
                ->name('municipalities.show');
            Route::put('/{id}', [MunicipalityController::class, 'update'])
                ->name('municipalities.update');
            Route::delete('/{id}', [MunicipalityController::class, 'destroy'])
                ->name('municipalities.destroy');
        });


        Route::prefix('issue-categories')->group(function () {
            Route::get('/', [IssueCategoryController::class, 'index'])
                ->name('issue-categories.index');
            Route::post('/', [IssueCategoryController::class, 'store'])
                ->name('issue-categories.store');
            Route::get('/{id}', [IssueCategoryController::class, 'show'])
                ->name('issue-categories.show');
            Route::put('/{id}', [IssueCategoryController::class, 'update'])
                ->name('issue-categories.update');
            Route::delete('/{id}', [IssueCategoryController::class, 'destroy'])
                ->name('issue-categories.destroy');
        });


        Route::prefix('reports')->group(function () {
            // Основные отчёты
            Route::get('/', [ReportsController::class, 'index'])->name('reports.index');
            Route::post('/', [ReportsController::class, 'store'])->name('reports.store');
            Route::get('/{id}', [ReportsController::class, 'show'])->name('reports.show');
            Route::put('/{id}', [ReportsController::class, 'update'])->name('reports.update');
            Route::delete('/{id}', [ReportsController::class, 'destroy'])->name('reports.destroy');

            // Входящие отчёты
            Route::prefix('incoming')->group(function () {
                Route::get('/mine', [IncomingReportsController::class, 'mine'])
                    ->middleware('auth')
                    ->name('reports.incoming.mine');
                Route::get('/', [IncomingReportsController::class, 'index'])->name('reports.incoming.index');
                Route::post('/', [IncomingReportsController::class, 'store'])
                    ->middleware('auth')
                    ->name('reports.incoming.store');
                Route::post('/pdf-incoming-report', [\App\Http\Controllers\PdfController::class, 'generateIncomingPdf'])->name('reports.incoming.pdf-report');
                Route::get('/{id}', [IncomingReportsController::class, 'show'])->name('reports.incoming.show');
                Route::put('/{id}', [IncomingReportsController::class, 'update'])->name('reports.incoming.update');
                Route::delete('/{id}', [IncomingReportsController::class, 'destroy'])->name('reports.incoming.destroy');
            });

            // Итоговые отчёты
            Route::prefix('result')->group(function () {
                Route::get('/', [ResultReportsController::class, 'index'])->name('reports.result.index');
                Route::post('/', [ResultReportsController::class, 'store'])->name('reports.result.store');
                Route::get('/{id}', [ResultReportsController::class, 'show'])->name('reports.result.show');
                Route::put('/{id}', [ResultReportsController::class, 'update'])->name('reports.result.update');
                Route::delete('/{id}', [ResultReportsController::class, 'destroy'])->name('reports.result.destroy');
            });

            // Заявки на мероприятия
            Route::prefix('events')->group(function () {
                Route::get('/', [EventRequestsController::class, 'index'])->name('reports.events.index');
                Route::post('/', [EventRequestsController::class, 'store'])->name('reports.events.store');
                Route::post('/pdf-event-report', [\App\Http\Controllers\PdfController::class, 'generateEventPdf'])->name('reports.event.pdf-report');
                Route::get('/{id}', [EventRequestsController::class, 'show'])->name('reports.events.show');
                Route::put('/{id}', [EventRequestsController::class, 'update'])->name('reports.events.update');
                Route::delete('/{id}', [EventRequestsController::class, 'destroy'])->name('reports.events.destroy');
            });
        });


        Route::prefix('forms')

            ->group(function () {
                // Заявка волонтера
                Route::post('/volunteer-job-pdf', [\App\Http\Controllers\PdfController::class, 'generateVolunteerPdf']);

            });

        // 🔹 Экспорты
        Route::prefix('exports')

            ->group(function () {
                Route::get('/agents', [AgentController::class, 'export'])->name('exports.agents');
                Route::get('/birthdays', [BirthdayController::class, 'export'])->name('exports.birthdays');
                Route::get('/admins', [UserController::class, 'exportAdmins'])->name('exports.admins');
                Route::get('/users', [UserController::class, 'export'])->name('exports.users');
                Route::get('/products', [ProductController::class, 'export'])->name('exports.products');
                Route::get('/categories', [ProductCategoryController::class, 'export'])->name('exports.categories');
                Route::get('/clients', [CustomerController::class, 'export'])->name('exports.clients');
                Route::get('/suppliers', [SupplierController::class, 'export'])->name('exports.suppliers');
                Route::get('/sales-history', [SaleController::class, 'export'])->name('exports.salesHistory');
                Route::post('/full', [AdminController::class, 'exportFull'])->name('exports.full');
            });

        Route::prefix('admins')

            ->group(function () {
                // Список всех продаж
                Route::get('/', [AdminController::class, 'index']);
                // Создать новую продажу
                Route::post('/download-report', [AdminController::class, 'downloadReport']);
                Route::post('/download-personal-report', [AdminController::class, 'downloadPersonalReport']);
                // Получить конкретную продажу по ID
            });

      //  Route::post('/users/self', [\App\Http\Controllers\TelegramController::class, "getSelf"]);

        Route::prefix('users')

            ->group(function () {
                // Список всех пользователей
                Route::get('/', [UserController::class, 'index']);
                // Создать нового пользователя
                Route::post('/', [UserController::class, 'store']);
                // Получить конкретного пользователя по ID
                Route::get('/{id}', [UserController::class, 'show']);

                // Обновить данные пользователя
                Route::put('/{id}', [UserController::class, 'update']);
                Route::patch('/{id}', [UserController::class, 'update']);


                // Удалить пользователя
                Route::delete('/{id}', [UserController::class, 'destroy']);
                // 🔹 Дополнительные маршруты для ролей и статусов

                Route::get('/{id}/tg', [UserController::class, 'getTelegramLink']);
                // Изменить роль пользователя
                Route::post('/{id}/role', [UserController::class, 'updateRole']);
                // Изменить процент
                Route::get('/{id}/percent', [UserController::class, 'updatePercent']);
                // Изменить статус работы (is_work)
                Route::post('/{id}/work-status', [UserController::class, 'updateWorkStatus']);
                // Заблокировать пользователя
                Route::get('/{id}/block', [UserController::class, 'block']);
                // Разблокировать пользователя
                Route::get('/{id}/unblock', [UserController::class, 'unblock']);
                Route::post('/primary', [UserController::class, 'primary']);
            });
    });


Route::get("/app/{any?}", [\App\Http\Controllers\SystemController::class, "homePage"])
    ->where('any', '.*');

