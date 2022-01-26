<?php
// Route::get('/', 'TicketController@create');

use Illuminate\Http\Request;

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
    Route::get('weeksInMonth', 'HomeController@weeksInMonth')->name('weeksInMonth');
    Route::get('statPanel', 'HomeController@statPanel')->name('statPanel');


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

    //Work Clock
    Route::resource('/workclock', 'WorkClockController');

    // Tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::delete('destroy', 'TicketsController@massDestroy')->name('massDestroy');
        Route::post('media', 'TicketsController@storeMedia')->name('storeMedia');
        Route::post('comment/{ticket}', 'TicketsController@storeComment')->name('storeComment');
        Route::get('data', 'TicketsController@data')->name('data');
        Route::get('report', 'TicketsController@showReport')->name('showReport');
        Route::get('getReport', 'TicketsController@getReport')->name('getReport');
        Route::get('getComments', 'TicketsController@getComments')->name('getComments');
        Route::put('quick-edit/{ticket_id}', 'TicketsController@quickEdit')->name('quickEdit');
        Route::post('recalculate-duration', 'TicketsController@recalculateDuration')->name('recalculate-duration');
    });
    Route::resource('tickets', 'TicketsController');

    // Comments
    Route::get('comments/data', 'CommentsController@data')->name('comments.data');
    Route::delete('comments/destroy', 'CommentsController@massDestroy')->name('comments.massDestroy');
    Route::resource('comments', 'CommentsController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Projects
    Route::delete('projects/destroy', 'ProjectsController@massDestroy')->name('projects.massDestroy');
    Route::get('projects/data', 'ProjectsController@data')->name('projects.data');
    Route::resource('projects', 'ProjectsController');

    //Notif
    Route::get('notif', 'NotifController@index')->name('notif');
    Route::post('notif', 'NotifController@markRead')->name('notif.markRead');

    //Profile
    Route::resource('profile', 'ProfileController');

});


Route::view('test', 'test');
