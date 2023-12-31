<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\DispatchRiderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\WebNotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(['middleware' => []], function () {

    Route::get('/login', function () {
        return view('other.login');
    })->name('login');
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/logout', function () {
        $role = Auth::user()->role;
        Auth::logout('user');
        if ($role == 'administrator') {
            return redirect('/admin-login');
        }
        return redirect('/login');
    });


    // send sms
    Route::get('/send-message', [SmsController::class, 'sendNow']);
    Route::get('/sendm/{to}/{body}', [Controller::class, 'sendSms']);



    Route::get('/admin-login', [AdminController::class, 'loginIndex']);
    Route::get('/admin-login', [AdminController::class, 'loginIndex'])->name('login');
    Route::get('/customer-login', [CustomerController::class, 'loginIndex'])->name('customer-login')->middleware('redirect.customer.authenticated');
    Route::post('/staff-login', [AuthController::class, 'staffLogin']);
    Route::post("/customer-login", [AuthController::class, 'customerLogin'])->name('auth.customer-login');

    Route::group(['prefix' => '/admin', 'as' => 'admin.', 'middleware' => ['auth', 'administrator']], function () {
        Route::get('/add-staff', [AdminController::class, 'addStaffIndex']);
        Route::get('/staff-list', [AdminController::class, 'viewAllStaff']);
        Route::get('/staff/{staff_id}', [AdminController::class, 'staffProfileIndex']);
        Route::post('/add-staff', [StaffController::class, 'addStaff']);
        Route::get('/dashboard', [AdminController::class, 'dashboardIndex']);
    });

    Route::get('/login', function () {
        return view('other.login');
    });

    Route::group(['prefix' => '/customer', 'as' => 'customer.', 'middleware' => ['customer.auth'], 'controller' => CustomerController::class], function () {
        Route::get('/orders', 'viewOrders')->name('view-orders');
        Route::match(["GET", "POST"], '/logout', [AuthController::class, 'customerLogout'])->name('logout');
        Route::get('/past-orders', 'viewAllPastOrders')->name('past-orders');
        Route::get('/profile', 'myProfile')->name('my-profile');
        Route::post('/store-token', [WebNotificationController::class, 'storeToken'])->name('store.token');
        Route::get('/send-web-notification', [WebNotificationController::class, 'sendWebNotification'])->name('send.web-notification');
//        Route::post('save-subscription', 'saveSubscription');
        Route::post('check-token', [WebNotificationController::class, 'checkToken'])->name('check-token');
//        Route::post('delete-subscription', 'deleteSubscription');

        Route::get('/order/{order_id}', 'orderInfo');
    });



    Route::group(['prefix' => '/delivery', 'as' => 'delivery.', 'middleware' => ['auth']], function () {
        Route::get('/dashboard', [DeliveryController::class, 'index']);
        Route::get('/ptd', [DeliveryController::class, 'ptdIndex']);
        Route::get('/ready/{id}', [DeliveryController::class, 'readyForDelivery']);
        Route::get('/ready', [DeliveryController::class, 'readyForDeliveryIndex']);
        Route::post('/assgin_rider', [DeliveryController::class, 'assignRider']);
        Route::get('/en_route', [DeliveryController::class, 'enRoute']);
    });




    Route::group(['prefix' => '/staff', 'as' => 'staff.', 'middleware' => ['auth', 'marketer']], function () {

        Route::get('/dashboard', function () {
            return view('other.index');
        });

        Route::get('/add-customer', function () {
            return view('other.customers.create_customer');
        });

        Route::controller(CustomerController::class)->group(function () {
            Route::post('add-customer', [CustomerController::class, 'addCustomer']);
            Route::get('customer-list', [CustomerController::class, 'customerList']);
            Route::get('customer/{customer_id}', [CustomerController::class, 'customerProfile']);
        });


        //Order Controller
        Route::controller(OrderController::class)->group(function () {
            Route::get('create-order', 'createOrderIndex')->name('create-order');
            Route::post('create-order', 'createOrder')->name('create-order');
            Route::get('orders', 'viewOrders')->name('view-orders');
            Route::get('order/{id}', 'viewOrder')->name('view-order');
            Route::post('order/update-status/{id}', 'updateOrderStatus')->name('update-order-status');
            Route::post('update-rider', 'updateDispatchRider')->name('update-order-rider');
            Route::post('push-to-delivery', 'pushToDelivery');
            Route::get('under-delivery', 'underDelivery');

        });
    });

    Route::group(['prefix' => '/dispatch', 'as' => 'dispatch.', 'middleware' => ['auth', 'dispatch_rider']], function () {
        Route::get('/dashboard', [DispatchRiderController::class, 'Index']);
    });


    Route::group(['prefix' => '/designer', 'as' => 'designer.',], function () {
        Route::get('/overview', [DesignerController::class, 'Index']);
        Route::get('/undesigned', [DesignerController::class, 'undesigned']);
        Route::get('/selected', [DesignerController::class, 'selected']);
        Route::get('/completed', [DesignerController::class, 'completed']);
        Route::get('/m/{id}', [DesignerController::class, 'allMarketerDesign']);
        Route::post('/select_design', [DesignerController::class, 'selectDesign']);
        Route::post('/complete_design', [DesignerController::class, 'completeDesign']);
    });
});
