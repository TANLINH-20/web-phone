<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Dashboard
Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index');

// JWT Authentication
Route::get('/auth', 'App\Http\Controllers\UserController@getAuthenticatedUser');
Route::post('/register', 'App\Http\Controllers\UserController@register');
Route::post('/login', 'App\Http\Controllers\UserController@login');

// Address
Route::get('/user/default-address', 'App\Http\Controllers\UserAddressController@show');
Route::post('/user/create-user-address', 'App\Http\Controllers\UserAddressController@createUser');
Route::post('/user/address', 'App\Http\Controllers\UserAddressController@store');

// Product
Route::get('/products', 'App\Http\Controllers\ProductController@index');
Route::get('/product/categories/{id}', 'App\Http\Controllers\ProductController@new');
Route::get('/products/{id}', 'App\Http\Controllers\ProductController@show');
Route::get('/product/hot-deal', 'App\Http\Controllers\ProductDealsController@hotDeals');
Route::post('/products', 'App\Http\Controllers\ProductController@store');
Route::delete('/products/{id}', 'App\Http\Controllers\ProductController@destroy');
Route::post('/product/{id}', 'App\Http\Controllers\ProductController@update');

// Product Orders
Route::post('/stripe', 'App\Http\Controllers\ProductOrdersController@stripePost');
Route::post('/products/orders', 'App\Http\Controllers\ProductOrdersController@store');

// Product Categories
Route::get('/product/categories', 'App\Http\Controllers\ProductCategoriesController@index');
Route::get('/product/category', 'App\Http\Controllers\ProductCategoriesController@index1');
Route::get('/product/categories/{id}/top-selling', 'App\Http\Controllers\ProductCategoriesController@topSelling');
Route::get('/product/categories/{id}/new', 'App\Http\Controllers\ProductCategoriesController@new');
Route::get('/product/category/{id}', 'App\Http\Controllers\ProductCategoriesController@show');
Route::put('/product/categories/{id}', 'App\Http\Controllers\ProductCategoriesController@update');
Route::delete('/product/categories/{id}', 'App\Http\Controllers\ProductCategoriesController@destroy');
Route::post('/product/category', 'App\Http\Controllers\ProductCategoriesController@store');

// Product Shopping Cart
Route::get('/product/cart-list/count', 'App\Http\Controllers\ShoppingCartController@cartCount');
Route::get('/product/cart-list', 'App\Http\Controllers\ShoppingCartController@index');
Route::post('/products/cart-list', 'App\Http\Controllers\ShoppingCartController@store');
Route::post('/product/cart-list/guest', 'App\Http\Controllers\ShoppingCartController@guestCart');
Route::put('/product/cart-list/{id}', 'App\Http\Controllers\ShoppingCartController@update');
Route::delete('/products/cart-list/{id}', 'App\Http\Controllers\ShoppingCartController@destroy');

// Product Wishlist
Route::get('/product/wishlist/count', 'App\Http\Controllers\ProductWishlistController@count');
Route::get('/product/wishlist', 'App\Http\Controllers\ProductWishlistController@index');
Route::post('/products/wishlist', 'App\Http\Controllers\ProductWishlistController@store');
Route::delete('/product/wishlist/{id}', 'App\Http\Controllers\ProductWishlistController@destroy');

// Product Stocks
Route::get('/product/stocks/{id}', 'App\Http\Controllers\StockController@show');


//search Product
Route::get('/search','App\Http\Controllers\ProductController@search');

// Newsletter
Route::post('/newsletter', 'App\Http\Controllers\NewsletterController@store');

//News
Route::get('/news','App\Http\Controllers\NewsController@index');
Route::get('/news/{id}','App\Http\Controllers\NewsController@show');
Route::post('/news','App\Http\Controllers\NewsController@store');
//contact
Route::post('/contact','App\Http\Controllers\ContactController@store');
//feedback
Route::post('/feedback','App\Http\Controllers\FeedbackController@store');

//Review

Route::get('/reviews/{productId}','App\Http\Controllers\ReviewController@index');
Route::post('/reviews/{productId}', 'App\Http\Controllers\ReviewController@store');
