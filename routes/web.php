<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'FriendsOfBotble\Yoomoney\Http\Controllers', 'middleware' => ['core', 'web']], function () {
    Route::get('yoomoney/payment/callback', [
        'as'   => 'yoomoney.payment.callback',
        'uses' => 'YoomoneyController@getCallback',
    ]);
});
