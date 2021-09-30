<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Project;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\User;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        {
            abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
            $projects = Project::all();
    
            return view('admin.projects.index', compact('projects'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assigned_to_users = User::whereHas('roles', function($query) {
            $query->whereId(2);
        })
        ->pluck('name', 'id')
        ->prepend(trans('global.pleaseSelect'), '');

        return view('admin.projects.create', compact('assigned_to_users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        // dd($request->assign_user_id);
        // $get_user = User::find($request->assign_user_id);
        // $pm_name = $get_user->name;
        // $pm_email = $get_user->email;
        // dd($pm_email);
        $project = Project::create($request->all());
        $project->user()->attach($request->assign_user_id, ['is_pm' => true]);


        // $project = Project::create([
        //     'name'      =>  $request->name,
        //     'pm_name'   =>  $pm_name,
        //     'pm_email'  =>  $pm_email,
        // ]);

        return redirect()->route('admin.projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        // dd($project);
        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pm = Project::find($project->id)->user()->where('is_pm', true)->first()->pivot->user_id ?? null;
        // dd($pm);

        $assigned_to_users = User::whereHas('roles', function($query) {
            $query->whereId(2);
        })
        ->pluck('name', 'id')
        ->prepend(trans('global.pleaseSelect'), '');

        return view('admin.projects.edit', compact('project', 'assigned_to_users', 'pm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        // dd($project);
        $project->update($request->all());
        $project->user()->detach($request->temp_pm);
        $project->user()->attach($request->assign_user_id, ['is_pm' => true]);

        return redirect()->route('admin.projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->delete();

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        Project::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
