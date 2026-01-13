<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderConfirmationMail;
use App\Models\User;

use App\Models\Order;


Route::get('/', function () {
    return view('welcome');
});


// Route::get('/test-mail', function () {
//     Mail::raw('Hello SMTP', function ($message) {
//         $message->to('test@test.com')
//                 ->subject('SMTP Test');
//     });

//     return 'Mail sent';
// });

// Route::get('/test-mail', function () {
//     Mail::raw('Test email from Laravel', function ($message) {
//         $message->to('eleganceshop729@gmail.com')
//                 ->subject('Test Gmail SMTP');
//     });

//     return 'Email sent!';
// });



