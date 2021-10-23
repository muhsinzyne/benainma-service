<?php

Route::group(['prefix' => 'api', 'namespace' => 'MuhsinZyne\BenainmaService\Controllers\Api', ], function () {
    Route::get('service/check', 'CheckController@index');
});
