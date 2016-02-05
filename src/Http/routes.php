<?php
/*
 * Include all files of Route directory
 */
Route::group(['middleware' => 'cors'], function() {
    foreach (glob(__DIR__ . '/Routes/*.php') as $file) {
        require $file;
    }
});