<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ExtraItemsController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Staff\Auth\StaffLoginController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffProfileController;
use App\Http\Controllers\Staff\StaffCustomersController;
use App\Http\Controllers\Staff\StaffServicesController;
use App\Http\Controllers\Staff\StaffTransactionsController;
use App\Http\Controllers\Staff\StaffPaymentsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin routes group
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/weekly-sales', [DashboardController::class, 'getWeeklySales'])->name('weekly-sales');
    
    // Customers CRUD
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers');
    Route::post('/customers', [CustomersController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomersController::class, 'show'])->name('customers.show');
    Route::put('/customers/{customer}', [CustomersController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomersController::class, 'destroy'])->name('customers.destroy');
    
    // Service Categories CRUD
    Route::get('/service-categories-data', [ServiceCategoryController::class, 'getCategoriesData'])->name('service-categories.data');
    Route::get('/service-categories', [ServiceCategoryController::class, 'index'])->name('service-categories');
    Route::post('/service-categories', [ServiceCategoryController::class, 'store'])->name('service-categories.store');
    Route::put('/service-categories/{serviceCategory}', [ServiceCategoryController::class, 'update'])->name('service-categories.update');
    Route::delete('/service-categories/{serviceCategory}', [ServiceCategoryController::class, 'destroy'])->name('service-categories.destroy');
    Route::get('/service-categories/{serviceCategory}', [ServiceCategoryController::class, 'show'])->name('service-categories.show');
    Route::get('/service-categories/{serviceCategory}/json', [ServiceCategoryController::class, 'showJson'])->name('service-categories.show.json');

    // Service Types CRUD
    Route::get('/services', [ServiceTypeController::class, 'index'])->name('services');
    Route::post('/service-types', [ServiceTypeController::class, 'store'])->name('service-types.store');
    Route::put('/service-types/{serviceType}', [ServiceTypeController::class, 'update'])->name('service-types.update');
    Route::delete('/service-types/{serviceType}', [ServiceTypeController::class, 'destroy'])->name('service-types.destroy');
    Route::get('/service-types/{serviceType}/json', [ServiceTypeController::class, 'showJson'])->name('service-types.show.json');
    Route::get('/service-types/{serviceType}', [ServiceTypeController::class, 'show'])->name('service-types.show');

    // Staff CRUD (Admin managing staff)
    Route::get('/staff', [StaffController::class, 'index'])->name('staff');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::get('/staff/{staff}', [StaffController::class, 'show'])->name('admin.staff.show');
    
    // Transactions CRUD
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/extra-items/{id}', [TransactionController::class, 'getExtraItemsTotal'])->name('transactions.extra-items');
    Route::get('/transactions/service-price/{id}', [TransactionController::class, 'getServicePrice'])->name('transactions.service-price');  // MOVED INSIDE THE GROUP
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    
    // Transaction Extra Items Routes
    Route::post('/transaction-extra-items', [TransactionController::class, 'addExtraItem'])->name('transaction-extra-items.store');
    Route::delete('/transaction-extra-items/{id}', [TransactionController::class, 'removeExtraItem'])->name('transaction-extra-items.destroy');
    
    // Payments CRUD
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');

    // Extra Items CRUD
    Route::get('/extra-items', [ExtraItemsController::class, 'index'])->name('extra-items');
    Route::post('/extra-items', [ExtraItemsController::class, 'store'])->name('extra-items.store');
    Route::put('/extra-items/{extraItem}', [ExtraItemsController::class, 'update'])->name('extra-items.update');
    Route::delete('/extra-items/{extraItem}', [ExtraItemsController::class, 'destroy'])->name('extra-items.destroy');
    Route::get('/extra-items/{extraItem}', [ExtraItemsController::class, 'show'])->name('extra-items.show');

    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::post('/reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
});

// Staff Routes
Route::prefix('staff')->name('staff.')->group(function () {
    // Guest routes for staff login
    Route::middleware('guest:staff')->group(function () {
        Route::get('login', [StaffLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [StaffLoginController::class, 'login'])->name('login.submit');
    });
    
    // Authenticated staff routes
    Route::middleware('auth:staff')->group(function () {
        // Dashboard & Profile
        Route::get('dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', [StaffProfileController::class, 'index'])->name('profile');
        Route::put('profile', [StaffProfileController::class, 'update'])->name('profile.update');
        Route::post('logout', [StaffLoginController::class, 'logout'])->name('logout');
        
        // Staff Customers Routes (FULL CRUD)
        Route::get('customers', [StaffCustomersController::class, 'index'])->name('customers');
        Route::post('customers', [StaffCustomersController::class, 'store'])->name('customers.store');
        Route::put('customers/{customer}', [StaffCustomersController::class, 'update'])->name('customers.update');
        Route::delete('customers/{customer}', [StaffCustomersController::class, 'destroy'])->name('customers.destroy');
        Route::get('customers/{customer}', [StaffCustomersController::class, 'show'])->name('staff.customers.show');

        // Staff Services Routes
        Route::get('services', [StaffServicesController::class, 'index'])->name('services');
        Route::get('services/{service}', [StaffServicesController::class, 'show'])->name('services.show');
        
        // Staff Transactions Routes
        Route::get('transactions', [StaffTransactionsController::class, 'index'])->name('transactions');
        Route::get('transactions/{id}', [StaffTransactionsController::class, 'show'])->name('transactions.show');
        Route::get('transactions/extra-items/{id}', [StaffTransactionsController::class, 'getExtraItemsTotal'])->name('transactions.extra-items');
        Route::get('transactions/service-price/{id}', [StaffTransactionsController::class, 'getServicePrice'])->name('transactions.service-price');  // ADD THIS
        Route::post('transactions', [StaffTransactionsController::class, 'store'])->name('transactions.store');
        Route::put('transactions/{id}', [StaffTransactionsController::class, 'update'])->name('transactions.update');
        Route::delete('transactions/{id}', [StaffTransactionsController::class, 'destroy'])->name('transactions.destroy');

        /// Staff Payments Routes
        Route::get('payments', [StaffPaymentsController::class, 'index'])->name('payments');
        Route::get('payments/{id}', [StaffPaymentsController::class, 'show'])->name('payments.show');
        Route::post('payments', [StaffPaymentsController::class, 'store'])->name('payments.store');
        Route::put('payments/{id}', [StaffPaymentsController::class, 'update'])->name('payments.update');
        Route::delete('payments/{id}', [StaffPaymentsController::class, 'destroy'])->name('payments.destroy');
    });
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';