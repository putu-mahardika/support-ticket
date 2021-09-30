<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Project;
use App\Role;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all();
        $users_project = DB::table('users')
                ->join('user_project', 'user_project.user_id', '=', 'users.id')
                ->join('projects', 'projects.id', '=', 'user_project.project_id')
                ->select('users.id', 'projects.name as project_name')
                ->where('user_project.is_pm', 0)
                ->get();
        
        // dd($users);

        return view('admin.users.index', compact('users', 'users_project'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $projects = Project::all()->pluck('name', 'id');

        return view('admin.users.create', compact('roles', 'projects'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->project()->attach($request->project);
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $projects = Project::all()->pluck('name', 'id');

        $user->load('roles', 'project');
        // dd($user);

        return view('admin.users.edit', compact('roles', 'user', 'projects'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // dd($request);
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
        $user->project()->detach();
        $user->project()->attach($request->project);

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
