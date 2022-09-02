<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\sections;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = DB::table('sections')->get();
        return view('sections.sections', ['sections' => $sections]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate
        (
            [
                'section_name' => 'required|unique:sections|max:50|string',
                'description'  => 'required|max:50|string',
            ],
            [

                'section_name.required' =>'يرجي ادخال اسم القسم',
                'section_name.unique'   =>'اسم القسم مسجل مسبقا',
                'section_name.max'      =>'اسم القسم لايمكن ان يكون اكثر من 50 حرف',
                'section_name.string'   =>'اسم القسم يحتوي علي احرف فقط ',

                'description.required'  =>'يرجي ادخال الملاحظات',
                'description.max'       =>'الملاحظات لايمكن ان تكون اكثر من 50 حرف',
                'description.string'    =>' الملاحظات تحتوي علي احرف فقط ',

            ]
        );

        sections::create([
            'section_name'   => $request->section_name,
            'description'    => $request->description,
            'created_by'     => (Auth::user()->name)
        ]);

        session()->flash('Add','تم أضافة القسم بنجاح ');
        return redirect(to:'/sections');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $section = $request->validate
        (
            [
                'section_name' => 'required|string|unique:sections,section_name,'.$id,
                'description'  => 'required|max:50|string',
            ],
            [

                'section_name.required' =>'يرجي ادخال اسم القسم',
                'section_name.unique'   =>'اسم القسم مسجل مسبقا',
                'section_name.string'   =>'اسم القسم يحتوي علي احرف فقط ',

                'description.max'       =>'الملاحظات لايمكن ان تكون اكثر من 50 حرف',
                'description.string'    =>' الملاحظات تحتوي علي احرف فقط ',

            ]
        );

        $affected = DB::table('sections')
            ->where('id', $id)
            ->update($section);

        if($affected)
        {
            session()->flash('edit','تم تعديل القسم بنجاج');
        }
        else
        {
            session()->flash('Error','لقد حدث خطأ في التعديل ');
        }
        return redirect('/sections');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        $deleted = DB::table('sections')->where('id',$id)->delete();
        if($deleted)
        {
            session()->flash('delete','تم حذف القسم بنجاج');
        }
        else
        {
            session()->flash('Error','لقد حدث خطأ في الحذف ');
        }
        return redirect('/sections');
    }
}
