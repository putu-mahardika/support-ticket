<?php
// Route::get('/', 'TicketController@create');
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', function () {
    $route = Gate::denies('dashboard_access') ? 'admin.tickets.index' : 'admin.home';
    if (session('status')) {
        return redirect()->route($route)->with('status', session('status'));
    }

    return redirect()->route($route);
});

Auth::routes(['register' => false]);

Route::post('tickets/media', 'TicketController@storeMedia')->name('tickets.storeMedia');
Route::post('tickets/comment/{ticket}', 'TicketController@storeComment')->name('tickets.storeComment');
Route::resource('tickets', 'TicketController')->only(['show', 'create', 'store']);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/getJumlahTiketHarian', 'HomeController@getJumlahTiketHarian')->name('getJumlahTiketHarian');
    Route::get('/getLastComment', 'HomeController@getLastComment')->name('getLastComment');

    Route::get('/getDataDoughnut', 'HomeController@getDataDoughnut')->name('getDataDoughnut');

    Route::get('/getTicketsThisWeek', 'HomeController@getTicketsThisWeek')->name('getTicketsThisWeek');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Statuses
    Route::delete('statuses/destroy', 'StatusesController@massDestroy')->name('statuses.massDestroy');
    Route::resource('statuses', 'StatusesController');

    // Priorities
    Route::delete('priorities/destroy', 'PrioritiesController@massDestroy')->name('priorities.massDestroy');
    Route::resource('priorities', 'PrioritiesController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');

    // Working Logs
    Route::get('/workinglogs', 'WorkingLogsController@index')->name('workinglogs.index');

    //Work Clock
    Route::resource('/workclock', 'WorkClockController');

    // Tickets
    Route::delete('tickets/destroy', 'TicketsController@massDestroy')->name('tickets.massDestroy');
    Route::post('tickets/media', 'TicketsController@storeMedia')->name('tickets.storeMedia');
    Route::post('tickets/comment/{ticket}', 'TicketsController@storeComment')->name('tickets.storeComment');
    Route::get('tickets/report', 'TicketsController@showReport')->name('tickets.showReport');
    Route::get('tickets/getReport', 'TicketsController@getReport')->name('tickets.getReport');
    Route::get('tickets/getComments', 'TicketsController@getComments')->name('tickets.getComments');
    Route::resource('tickets', 'TicketsController');

    // Comments
    Route::delete('comments/destroy', 'CommentsController@massDestroy')->name('comments.massDestroy');
    Route::resource('comments', 'CommentsController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Projects
    Route::delete('projects/destroy', 'ProjectsController@massDestroy')->name('projects.massDestroy');
    Route::resource('projects', 'ProjectsController');

    //Notif
    Route::get('notif', 'NotifController@index')->name('notif');
    Route::post('notif', 'NotifController@markRead')->name('notif.markRead');

});


Route::view('test', 'test');
