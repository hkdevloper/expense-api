<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\registerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function (Request $request) {
    return 'Server is running! 🙂';
});

Route::get('/test', function (Request $request) {
    $additionalSources = ['Investment Dividends', 'Side Job', 'Refund', 'Savings Withdrawal'];
    $additionalSpentOn = ['Electronics', 'Home Decor', 'Vacation', 'Education', 'Fitness'];
    $additionalRemarks = [
        'Purchase of a new laptop',
        'Home furniture upgrade',
        'Weekend getaway expenses',
        'Tuition fees for a course',
        'Gym membership renewal',
        'Dividends from stock investments',
        'Income from a part-time job',
        'Refund from a canceled subscription',
        'Withdrawal from savings account',
        'Shopping for electronic gadgets',
        'Expenses during a family vacation',
    ];

    $sources = array_merge(['Bank', 'Cash', 'Credit Card', 'Online Payment'], $additionalSources);
    $spentOn = array_merge(['Online Shopping', 'Dining Out', 'Medical Bills', 'Rent', 'Transportation'], $additionalSpentOn);
    $remarks = array_merge([
        'Monthly grocery shopping',
        'Dinner with friends',
        'Doctor visit for regular checkup',
        'Payment for internet services',
        'Fuel for the car',
        'Movies and entertainment',
        'Salary deposit',
        'Freelance project payment',
        'Returns from investments',
        'Received a gift',
        'Other miscellaneous income',
    ], $additionalRemarks);
    $transactionsPerDay = rand(1, 10);
    for ($i = 0; $i < 2 * 365; $i++) {
        $transactionDate = date('Y-m-d H:i:s', strtotime('-' . $i . ' days'));
        for ($j = 0; $j < $transactionsPerDay; $j++) {
            DB::table('income_expenses')->insert([
                'category_id' => TransactionCategory::pluck('id')->random(),
                'currency_id' => 67,
                'source' => $sources[array_rand($sources)],
                'spent_on' => $spentOn[array_rand($spentOn)],
                'remarks' => $remarks[array_rand($remarks)],
                'amount' => rand(1, 1000),
                'transaction_date' => $transactionDate,
                'transaction_type' => ($j % 2 == 0) ? 'Income' : 'Expense',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $transactionDate,
                'updated_at' => $transactionDate,
            ]);
        }
    }

});

Route::group(['prefix' => 'v1'], function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::patch('/auth/refresh', [AuthController::class, 'refresh']);
    Route::post('/auth/register', [registerController::class, 'store']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
    Route::get('/user', [UserController::class, 'self']);
    Route::put('/user/password', [UserController::class, 'password']);
    Route::put('/user/update', [UserController::class, 'updateProfile']);
    Route::get('/user/profile', [UserController::class, 'self']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::put('/user/{id}', [UserController::class, 'update']);

    Route::get('/expense/category', [ExpenseCategoryController::class, 'index']);
    Route::post('/expense/category', [ExpenseCategoryController::class, 'store']);
    Route::get('/expense/category/{id}', [ExpenseCategoryController::class, 'show']);
    Route::put('/expense/category/{id}', [ExpenseCategoryController::class, 'update']);
    Route::delete('/expense/category/{id}', [ExpenseCategoryController::class, 'destroy']);

    Route::get('/income/category', [IncomeCategoryController::class, 'index']);
    Route::post('/income/category', [IncomeCategoryController::class, 'store']);
    Route::get('/income/category/{id}', [IncomeCategoryController::class, 'show']);
    Route::put('/income/category/{id}', [IncomeCategoryController::class, 'update']);
    Route::delete('/income/category/{id}', [IncomeCategoryController::class, 'destroy']);

    Route::get('/expense', [ExpenseController::class, 'index']);
    Route::post('/expense', [ExpenseController::class, 'store']);
    Route::get('/expense/summary', [ExpenseController::class, 'summary']);
    Route::get('/expense/{id}', [ExpenseController::class, 'show']);
    Route::put('/expense/{id}', [ExpenseController::class, 'update']);
    Route::delete('/expense/{id}', [ExpenseController::class, 'destroy']);

    Route::get('/income', [IncomeController::class, 'index']);
    Route::post('/income', [IncomeController::class, 'store']);
    Route::get('/income/summary', [IncomeController::class, 'summary']);
    Route::get('/income/{id}', [IncomeController::class, 'show']);
    Route::put('/income/{id}', [IncomeController::class, 'update']);
    Route::delete('/income/{id}', [IncomeController::class, 'destroy']);

    Route::get('/currency', [CurrencyController::class, 'index']);
    Route::post('/currency/{id}', [CurrencyController::class, 'update']);

    Route::get('/report/expense/months/summary', [ReportController::class, 'monthlyExpenseSummary']);
    Route::get('/report/income/months/summary', [ReportController::class, 'monthlyIncomeSummary']);
    Route::get('/report/transaction', [ReportController::class, 'transaction']);

    Route::get('/chart/income-expense/category', [ChartController::class, 'incomeExpenseCategories']);
    Route::get('/chart/income-expense/month-wise', [ChartController::class, 'incomeExpenseDataMonthWise']);
    Route::get('/chart/income-expense/category-wise', [ChartController::class, 'incomeExpenseDataMonthAndCategoryWise']);

});
