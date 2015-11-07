<?php

Route::group(['middleware' => 'cors'], function() {
    foreach (glob(__DIR__ . '/Routes/*.php') as $file) {
        require $file;
    }
});