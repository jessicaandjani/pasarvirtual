<?php

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
Route::get('/', function () {
    return view('welcome');
});

//Route for Categories
Route::get('/categories', 'CategoryController@getAllCategory');
Route::get('/categories/add', 'CategoryController@imageUpload');
Route::post('/categories/add', 'CategoryController@addCategory');
Route::post('/categories/edit/{id}', 'CategoryController@updateCategory');

//Route for Unit
Route::get('/units', 'UnitController@getAllUnit');
Route::get('/units/{id}', 'UnitController@getUnit');
Route::get('/unit/add', 'UnitController@unitPost');
Route::post('/units/add', 'UnitController@addUnit');
Route::post('/units/edit/{id}', 'UnitController@updateUnit');

//Route for Converter
Route::get('/converter', 'ConverterController@getAllConverter');
Route::get('/converter/{id}', 'ConverterController@getConverter');
Route::get('/converter/add/{unit_id}/{gram}', 'ConverterController@addConverter');
Route::get('/converter/edit/{unit_id}/{gram}', 'ConverterController@updateConverter');

//Route for Products
Route::get('/categories/{id}', 'ProductController@getAllProductByCategory');
Route::get('/product', 'ProductController@getAllProduct');
Route::get('/product/{id}', 'ProductController@getProduct');
Route::get('/product/search/{keyword}', 'ProductController@getSearchProduct');
Route::get('/products/add', 'ProductController@productAdd');
Route::post('/product/add', 'ProductController@addProduct');
// Route::get('/products/edit', 'ProductController@productAdd');
Route::post('/product/edit/{id}', 'ProductController@updateProduct');

//Route for Order
Route::get('/order', 'OrderController@getAllOrder');
Route::post('/order/add', 'OrderController@addOrder');

//Route for Shopping List
Route::get('/orderline', 'ShoppingListController@getAllOrderLine');
Route::post('/order/add/{id}', 'ShoppingListController@addShoppingList');

//Route for SMS
Route::get('/user-sms', 'SMSController@getUserId');
Route::get('/order-sms', 'SMSController@getOrder');
Route::get('/orderline-sms', 'SMSController@getOrderLine');
Route::post('/add/order-sms', 'SMSController@addOrder');
Route::get('/add/orderline-sms', 'SMSController@addOrderLine');

//Route for image
Route::get('/public/images/{categories}/{filename}', function($categories, $filename){
    $path = public_path('images/') . $categories . '/' . $filename;

    if(!File::exists($path)) {
        return response()->json(['message' => 'Image not found.'], 404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

//Route for SMS
Route::post('/sms/send', 'SMSController@sendMessage');
Route::post('/sms/receive', 'SMSController@receive');
