<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGradeRequest;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Grade;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         abort_if(Gate::denies('grade_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grade = Grade::all();

        return view('admin.grades.index', compact('grade'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        abort_if(Gate::denies('grade_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.grades.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGradeRequest $request)
    {
        $attributes = $request->all();
        $attributes['status'] = $request->has('status') ? true : false;
        $crmCustomer = Grade::create($attributes);

        return redirect()->route('admin.grades.index');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Grade $grade)
    {
        abort_if(Gate::denies('grade_user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.grades.edit', compact('grade'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        $attributes = $request->all();
        $attributes['status'] = $request->has('status') ? true : false;
        
        $grade->update($attributes);

        return redirect()->route('admin.grades.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade)
    {
        abort_if(Gate::denies('grade_single_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grade->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
