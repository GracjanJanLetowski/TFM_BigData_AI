<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProductController;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderAdminNotificationMail;
use App\Models\Order;


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



Route::get('/', 'App\Http\Controllers\HomeController@index')->name("home.index");
Route::get('/about', 'App\Http\Controllers\HomeController@about')->name("home.about");
Route::get('/products', 'App\Http\Controllers\ProductController@index')->name("product.index");
Route::get('/products/{id}', 'App\Http\Controllers\ProductController@show')->name("product.show");

Route::get('/cart', 'App\Http\Controllers\CartController@index')->name("cart.index");
Route::get('/cart/delete', 'App\Http\Controllers\CartController@delete')->name("cart.delete");
Route::post('/cart/add/{id}', 'App\Http\Controllers\CartController@add')->name("cart.add");

Route::get('/sobre-nosotros', function () {return view('sobre-nosotros');})->name('about');



Route::middleware('auth')->group(function () {
    Route::get('/cart/purchase', 'App\Http\Controllers\CartController@purchase')->name("cart.purchase");
    Route::get('/purchase/completed/{order}', 'App\Http\Controllers\CartController@purchaseCompleted')->name('purchase.completed');
    Route::get('/my-account/orders', 'App\Http\Controllers\MyAccountController@orders')->name("myaccount.orders");

    // Rutas de Comentarios
    Route::post('/products/{product}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Rutas de Valoraciones
    Route::post('/products/{product}/ratings', [RatingController::class, 'store'])->name('ratings.store');

    // Ruta de mi perfil y saldo
    Route::get('/mi-perfil', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    Route::post('/mi-perfil/balance', [App\Http\Controllers\UserController::class, 'addBalance'])->name('user.addBalance');
});


Route::get('/language/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'es'])) {
        session()->put('applocale', $lang);
        app()->setLocale($lang);
    }
    return redirect()->back();
})->name('language.switch');

// Rutas de admin
Route::middleware('admin')->group(function () {
    Route::get('/admin/orders/{id}', 'App\Http\Controllers\AdminOrderController@show')->name('admin.orders.show');
    Route::get('/admin/orders', 'App\Http\Controllers\AdminOrderController@index')->name('admin.orders.index');
    Route::get('/admin', 'App\Http\Controllers\Admin\AdminHomeController@index')->name("admin.home.index");
    Route::get('/admin/products', 'App\Http\Controllers\Admin\AdminProductController@index')->name("admin.product.index");
    Route::post('/admin/products/store', 'App\Http\Controllers\Admin\AdminProductController@store')->name("admin.product.store");
    Route::delete('/admin/products/{id}/delete', 'App\Http\Controllers\Admin\AdminProductController@delete')->name("admin.product.delete");
    Route::get('/admin/products/{id}/edit', 'App\Http\Controllers\Admin\AdminProductController@edit')->name("admin.product.edit");
    Route::put('/admin/products/{id}/update', 'App\Http\Controllers\Admin\AdminProductController@update')->name("admin.product.update");
    Route::get('/admin/dashboard', 'App\Http\Controllers\AdminDashboardController@index')->name('admin.dashboard');   
     
    Route::get('/admin/users', 'App\Http\Controllers\Admin\AdminUserController@index')->name('admin.users.index');
    Route::get('/admin/users/create', 'App\Http\Controllers\Admin\AdminUserController@create')->name('admin.users.create');
    Route::post('/admin/users', 'App\Http\Controllers\Admin\AdminUserController@store')->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', 'App\Http\Controllers\Admin\AdminUserController@edit')->name('admin.users.edit');
    Route::put('/admin/users/{id}', 'App\Http\Controllers\Admin\AdminUserController@update')->name('admin.users.update');
    Route::delete('/admin/users/{id}', 'App\Http\Controllers\Admin\AdminUserController@destroy')->name('admin.users.destroy');
    Route::post('/admin/users/{id}/balance', 'App\Http\Controllers\Admin\AdminUserController@addBalance')->name('admin.users.addBalance');
    });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



//Pruebas de envio de correo

//Con que te metas a las rutas de http://localhost:8000/preview-confirmation-mail y http://localhost:8000/preview-admin-mail Se ven los pedidos,
//tambien tiene que estar mailhog encendido el comando para testearlo es: php artisan send:test-email    se ve que llega en http://localhost:8025/# que es donde se ve mailhog

// Rutas para previsualizar correos (solo en desarrollo)
Route::get('/preview-confirmation-mail', function () {
    // Se usa un pedido de ejemplo; asegúrate de adaptar la estructura a tu modelo Order
    $order = \App\Models\Order::first() ?? new \App\Models\Order([
        'id'         => 123,
        'total'      => 150.00,
        'created_at' => now(),
        'user'       => (object)['name' => 'Juan Pérez', 'email' => 'juan@ejemplo.com']
    ]);
    return new \App\Mail\OrderConfirmationMail($order);
});

Route::get('/preview-admin-mail', function () {
    $order = \App\Models\Order::first() ?? new \App\Models\Order([
        'id'         => 123,
        'total'      => 150.00,
        'created_at' => now(),
        'user'       => (object)['name' => 'Juan Pérez', 'email' => 'juan@ejemplo.com']
    ]);
    return new \App\Mail\OrderAdminNotificationMail($order);
});


