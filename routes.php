<?php


Route::post(config('cms.backendUri').'/backend/auth/signin',['uses'=>'Jlab\Users\Controllers\Auth@login'])
    ->middleware('web');