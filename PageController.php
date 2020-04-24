<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gate;
use App\Page;
use Symfony\Component\HttpFoundation\Response;
class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         abort_if(Gate::denies('page_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $pages =Page::orderBy('id', 'desc')->get();
        return view('admin.page.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create()
    {
        //
        abort_if(Gate::denies('page_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
      //  $imageName = time().'.'.request()->image->getClientOriginalExtension();

  
      $imageName ='';
        if ($request->hasFile('page_image')) {
        $image = $request->file('page_image');
        
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/page_images');
        $image->move($destinationPath, $name);
        $imageName = $name;
    }
        $attributes = $request->all();
        $attributes['status'] = $request->has('status') ? 1 : 0;
        $attributes['page_slug'] = preg_replace('/\s+/', '-', strtolower($request->input('page_name')));
        $attributes['page_image'] = $imageName;	
         $id = Page::create($attributes);
        return redirect()->route('admin.page.index')->with('success','Page created successfully!');
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
     public function edit(Page $page)
    {
        //
        abort_if(Gate::denies('page_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.page.edit', compact('page'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        //
        abort_if(Gate::denies('page_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');
         $imageName ='';
        if ($request->hasFile('page_image')) {
        $image = $request->file('page_image');
        
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/page_images');
        $image->move($destinationPath, $name);
        $imageName = $name;
    }
        $attributes = $request->all();
        $attributes['status'] = $request->has('status') ? 1 : 0;
         $attributes['page_slug'] = preg_replace('/\s+/', '-', strtolower($request->input('page_name')));
         $attributes['page_image'] = $imageName;	
         $page->update($attributes);
          return redirect()->route('admin.page.index')->with('success','Page updated successfully!');
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        abort_if(Gate::denies('page_mgt'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $page->delete();

        return back()->with('success','Page deleted successfully!');
    }
}
