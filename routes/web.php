<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Models\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {

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

    Route::get('info-profile', function () {
        return view('profile/info-profile');
    })->name('info-profile');

    Route::get('user-profile', function () {
        return view('profile/user-profile');
    })->name('user-profile');

    Route::resource('banks', BankController::class);

    // Rota para exibir todos os bancos
    Route::get('banks', [BankController::class, 'index'])->name('banks.index');

    // Rota para exibir o formulário de criação de um novo banco
    Route::get('banks/create', [BankController::class, 'create'])->name('banks.create');

    // Rota para armazenar um novo banco
    Route::post('banks', [BankController::class, 'store'])->name('banks.store');

    // Rota para exibir o formulário de edição de um banco
    Route::get('banks/{id_bank}/edit', [BankController::class, 'edit'])->name('banks.edit');

    // Rota para atualizar o banco
    Route::put('banks/{id_bank}', [BankController::class, 'update'])->name('banks.update');

    // Rota para excluir o banco
    Route::delete('banks/{id_bank}', [BankController::class, 'destroy'])->name('banks.destroy');

    Route::resource('clients', ClientController::class);
    Route::post('clients', [ClientController::class, 'store'])->name('clients.store');


    // Rota para exibir todos os clientes
    Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
    Route::resource('categories', CategoryController::class);

    // Rota para exibir todas as categorias
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');

    // Rota para exibir o formulário de criação de uma nova categoria
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.index');

    // Rota para armazenar uma nova categoria
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');

    // Rota para exibir o formulário de edição de uma categoria
    Route::get('categories/{id_category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');

    // Rota para atualizar a categoria
    Route::put('categories/{id_category}', [CategoryController::class, 'update'])->name('categories.update');

    // Rota para excluir a categoria
    Route::delete('categories/{id_category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Rota para exibir todos os produtos (index)
    Route::get('products', [ProductController::class, 'index'])->name('products.index');

    // Rota para armazenar um novo produto
    Route::post('products', [ProductController::class, 'store'])->name('products.store');

    // Rota para exibir o formulário de edição de um produto (isso pode ser feito no modal também)
    Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');

    // Rota para atualizar o produto
    Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');

    // Rota para excluir um produto
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Editar perfil de usuário
    Route::get('/user-profile/{id}/edit', [InfoUserController::class, 'edit'])->name('user.edit');
    Route::post('/user-profile/{id}/edit', [InfoUserController::class, 'store'])->name('user.update');
    // Rota para atualizar o usuário com POST
    Route::post('/user-management/{id}/edit', [InfoUserController::class, 'update'])->name('user.update');

    // Criar um novo usuário
    Route::get('user-management', [InfoUserController::class, 'index'])->name('user-management');
    Route::get('/user-management/create', [InfoUserController::class, 'create'])->name('user.create');
    Route::post('/user-management/create', [InfoUserController::class, 'store'])->name('user.store');

    // Deletar usuário
    Route::delete('/user-management/{id}', [InfoUserController::class, 'destroy'])->name('user.delete');
    Route::post('/update-stock/{productId}', [SaleController::class, 'updateStock']);
    // Em routes/web.php
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

    Route::resource('sales', SaleController::class);
    // Definindo a rota para adicionar um produto à venda
    Route::post('/sales/{sale}/addProduct', [SaleController::class, 'addProduct'])->name('sales.addProduct');
    Route::get('tables', function () {
        return view('tables');
    })->name('tables');
    // Rota para buscar os dados de um cliente específico
    Route::get('/client/{id}/data', function ($id) {
        $client = Client::findOrFail($id);  // Tente encontrar o cliente com o ID fornecido
        return response()->json($client);  // Retorna os dados do cliente como JSON
    });
    Route::get('static-sign-in', function () {
        return view('static-sign-in');
    })->name('sign-in');

    Route::get('static-sign-up', function () {
        return view('static-sign-up');
    })->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
        return view('dashboard');
    })->name('sign-up');
});


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

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');
