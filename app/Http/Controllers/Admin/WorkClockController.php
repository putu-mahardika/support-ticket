<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Workclock;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkClockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workclocks = Workclock::all();
        return view('admin.workclock.index', compact('workclocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // abort_if(Gate::denies('workclock_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.workclock.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'day' => ['required', 'string', 'unique:workclock,day'],
            'time_start' => ['required'],
            'duration' => ['required', 'integer']
        ]);

        Workclock::create($request->all());
        return redirect()->route('admin.workclock.index')->with('status', 'Work Clock berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // abort_if(Gate::denies('workclock_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $workclock = Workclock::find($id);
        return view('admin.workclock.edit', compact('workclock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'day' => ['required', 'string', 'unique:workclock,day,'.$id],
            'time_start' => ['required'],
            'duration' => ['required', 'integer']
        ]);
        Workclock::find($id)->update($request->all());
        return redirect()->route('admin.workclock.index')->with('status', 'Workclock berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Workclock::find($id)->delete();
    }
}
