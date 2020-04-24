<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Slider;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         abort_if(Gate::denies('slider_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $Slider = Slider::all();
        return view('admin.slider.index', compact('Slider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('slider_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //
        return view('admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('slider_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');
          $imageName ='';
        if ($request->hasFile('slider_image')) {
        $image = $request->file('slider_image');
        
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/slider_image');
        $image->move($destinationPath, $name);
        $imageName = $name;
    }
        $attributes = $request->all();
         $attributes['slider_image'] = $imageName;	
        $attributes['status'] = $request->has('status') ? 1 : 0;
       
         $id = Slider::create($attributes);
        return redirect()->route('admin.slider.index')->with('success','Page created successfully!');
        //
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
    public function edit(Slider $slider)
    {
        //
        abort_if(Gate::denies('slider_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.slider.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        //
        abort_if(Gate::denies('slider_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');
         $imageName ='';
        if ($request->hasFile('slider_image')) {
        $image = $request->file('slider_image');
        
      $name = time().'.'.$image->getClientOriginalExtension();
        
        $destinationPath = public_path('/slider_image');
        $image->move($destinationPath, $name);
        $imageName = $name;
        
    }
        $attributes = $request->all();
        if(!empty($imageName))
        {
        $attributes['slider_image'] = $imageName;
        }
        $attributes['status'] = $request->has('status') ? 1 : 0;
       	
         $slider->update($attributes);
          return redirect()->route('admin.slider.index')->with('success','Slider updated successfully!');
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        abort_if(Gate::denies('slider_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }
}
