<?php


// Route::post(config('cms.backendUri').'/backend/auth/signin',['uses'=>'Jlab\Users\Controllers\Auth@login'])
//     ->middleware('web');

// // Overide Backend Login
Route::post(config('cms.backendUri').'/backend/auth/signin',['uses'=>'\Jlab\Users\Controllers\Auth@signin_onSubmit'])->middleware('web');

// // Logout
Route::any(config('cms.backendUri').'/backend/auth/signout','\Jlab\Users\Controllers\Auth@signout')->middleware('web');
