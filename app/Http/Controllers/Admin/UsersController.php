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
        $users = User::with('projects', 'roles')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        // $projects = Project::all()->pluck('name', 'id');
        $projects = DB::table('projects')
                ->join('user_project', 'user_project.project_id', '=', 'projects.id')
                ->join('users', 'users.id', '=', 'user_project.user_id', )
                ->select('projects.id as id', 'projects.name as name')
                ->where('user_project.is_pm', 1)
                ->get();

        // dd($projects);

        return view('admin.users.create', compact('roles', 'projects'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->projects()->attach($request->project);
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $projects = Project::all()->pluck('name', 'id');

        // $user->load('roles', 'project');
        $projects = DB::table('projects')
                ->join('user_project', 'user_project.project_id', '=', 'projects.id')
                ->join('users', 'users.id', '=', 'user_project.user_id', )
                ->select('projects.id as id', 'projects.name as name')
                ->where('user_project.is_pm', 1)
                ->get();
        // dd($user);

        return view('admin.users.edit', compact('roles', 'user', 'projects'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // dd($request);
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
        $user->projects()->detach();
        $user->projects()->attach($request->project);

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
