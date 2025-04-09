<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductUploadController;
use App\Http\Controllers\SaleController;
use App\Models\Client;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group. Now create something great!
*/

Route::group(['middleware' => 'auth'], function () {
    // Dashboard and general routes
    Route::get('/', [HomeController::class, 'home']);
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('billing', function () {
        return view('billing');
    })->name('billing');

    Route::get('profile', function () {
        return view('profile/profile');
    })->name('profile');

    Route::middleware(['auth'])->group(function () {
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        // Outras rotas, como 'store', 'edit', 'update', 'destroy', podem ser adicionadas aqui
    });

    // Rota para visualizar o cartão (irá redirecionar para as invoices)
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/banks/change/{bank}', [BankController::class, 'changeCard'])->name('banks.change');

    // Routes for managing user profiles
    Route::resource('banks', BankController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('categories', CategoryController::class);
    // Rota para listar os bancos
    Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
    // Routes for managing products (CRUD)
    Route::resource('products', ProductController::class);

    // Route to show the upload form for products
    Route::get('/products/upload', [ProductUploadController::class, 'showUploadForm'])->name('products.upload');
    Route::post('/products/upload', [ProductUploadController::class, 'upload'])->name('products.upload');

    // Rota para criar produtos a partir do arquivo PDF
    Route::post('/products/pdf/store', [ProductUploadController::class, 'store'])->name('products.pdf.store');

    // Rota para criar produtos manualmente
    Route::post('/products/manual/store', [ProductController::class, 'store'])->name('products.manual.store');

    // Routes for managing sales
    Route::resource('sales', SaleController::class);
    Route::post('/sales/{sale}/addProduct', [SaleController::class, 'addProduct'])->name('sales.addProduct');

    Route::post('/sales/{sale}/add-payment', [SaleController::class, 'addPayment'])->name('sales.addPayment');
    Route::put('/sales/{saleId}/payments/{paymentId}/update', [SaleController::class, 'updatePayment'])->name('sales.updatePayment');
    Route::delete('/sales/{saleId}/payments/{paymentId}/delete', [SaleController::class, 'deletePayment'])->name('sales.deletePayment');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::delete('/sales/item/{id}', [SaleController::class, 'destroySaleItem'])->name('sales.item.destroy');
    Route::put('/sales/{sale}/item/{item}', [SaleController::class, 'updateSaleItem'])->name('sales.item.update');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');

    Route::get('tables', function () {
        return view('tables');
    })->name('tables');
    Route::post('/update-stock/{productId}', [SaleController::class, 'updateStock']);

    // User Profile Update Routes
    Route::get('/user-profile/{id}/edit', [InfoUserController::class, 'edit'])->name('user.edit');
    Route::post('/user-profile/{id}/edit', [InfoUserController::class, 'store'])->name('user.update');

    // Additional routes for user management
    Route::get('user-management', [InfoUserController::class, 'index'])->name('user-management');
    Route::get('/user-management/create', [InfoUserController::class, 'create'])->name('user.create');
    Route::post('/user-management/create', [InfoUserController::class, 'store'])->name('user.store');
    Route::delete('/user-management/{id}', [InfoUserController::class, 'destroy'])->name('user.delete');
    Route::get('static-sign-in', function () {
        return view('static-sign-in');
    })->name('sign-in');

    Route::get('static-sign-up', function () {
        return view('static-sign-up');
    })->name('sign-up');
    Route::get('/login', function () {
        return view('dashboard');
    })->name('sign-up');

    // Logout route
    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);
});

// Routes for guest (unauthenticated) users
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
    Route::get('/login/forgot-password', [ResetController::class, 'create']);
    Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Routes for static pages like sign-in, sign-up
Route::get('static-sign-in', function () {
    return view('static-sign-in');
})->name('sign-in');

Route::get('static-sign-up', function () {
    return view('static-sign-up');
})->name('sign-up');

// Client data route
Route::get('/client/{id}/data', function ($id) {
    $client = Client::findOrFail($id);  // Retrieve the client by ID
    return response()->json($client);  // Return client data as JSON
});

// Route for searching products
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::post('/products/update-all', [ProductController::class, 'updateAll'])->name('products.updateAll');
Route::get('/login', function () {
    return view('session/login-session');
})->name('login');
