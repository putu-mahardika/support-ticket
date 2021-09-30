<?php

Route::prefix('v1')->name('api.')->group(function () {
    Route::name('auth.')->group(function () {
        Route::post('login', [\App\Http\Controllers\Api\V1\Auth\LoginController::class, 'login'])->name('login');
        Route::post('logout', [\App\Http\Controllers\Api\V1\Auth\LoginController::class, 'logout'])->name('logout');
    });
    Route::prefix('tickets')->name('tickets.')->middleware('auth:sanctum')->group(function () {
        // Route::get('get', [\App\Http\Controllers\Api\V1\Auth\TicketController::class, 'getTickets'])->name('getTickets');
        Route::post('create', [\App\Http\Controllers\Api\V1\Auth\TicketController::class, 'create'])->name('create');
    });
});

// Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
//     // Permissions
//     Route::apiResource('permissions', 'PermissionsApiController');

//     // Roles
//     Route::apiResource('roles', 'RolesApiController');

//     // Users
//     Route::apiResource('users', 'UsersApiController');

//     // Statuses
//     Route::apiResource('statuses', 'StatusesApiController');

//     // Priorities
//     Route::apiResource('priorities', 'PrioritiesApiController');

//     // Categories
//     Route::apiResource('categories', 'CategoriesApiController');

//     // Tickets
//     Route::post('tickets/media', 'TicketsApiController@storeMedia')->name('tickets.storeMedia');
//     Route::apiResource('tickets', 'TicketsApiController');

//     // Comments
//     Route::apiResource('comments', 'CommentsApiController');
// });
