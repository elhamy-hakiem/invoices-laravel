<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\sections;
use App\Models\products;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = DB::table('sections')->get();
        $products = DB::table('products')
                        ->leftJoin('sections', 'products.section_id', '=', 'sections.id')
                        ->select('products.*', 'sections.section_name')
                        ->get();
        return view('products.products', ['sections' => $sections],['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Validated = $request->validate
        (
            [
                'product_name' => 'required|unique:products|max:50|string',
                'description'  => 'required|max:50|string',
                'section_id'   => 'required|integer'
            ],
            [
                'product_name.required' =>'يرجي ادخال اسم المنتج',
                'product_name.unique'   =>'اسم المنتج مسجل مسبقا',
                'product_name.max'      =>'اسم المنتج لايمكن ان يكون اكثر من 50 حرف',
                'product_name.string'   =>'اسم المنتج يحتوي علي احرف فقط ',

                'description.required'  =>'يرجي ادخال الملاحظات',
                'description.max'       =>'الملاحظات لايمكن ان تكون اكثر من 50 حرف',
                'description.string'    =>' الملاحظات تحتوي علي احرف فقط ',

                'section_id.required'   =>'يرجي اختيار قسم',
                'section_id.integer'    =>'يرجي اختيار قسم صحيح'
            ]
        );

        products::create
        (
            [
                'product_name'  =>  $request->product_name,
                'description'   =>  $request->description,
                'section_id'    =>  $request->section_id
            ]
        );

        session()->flash('Add','تم أضافة المنتج بنجاح ');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $product = $request->validate
        (
            [
                'product_name' => 'required|max:50|string|unique:products,product_name,'.$id,
                'description'  => 'required|max:50|string',
                'section_id'   => 'required|integer'
            ],
            [
                'product_name.required' =>'يرجي ادخال اسم المنتج',
                'product_name.unique'   =>'اسم المنتج مسجل مسبقا',
                'product_name.max'      =>'اسم المنتج لايمكن ان يكون اكثر من 50 حرف',
                'product_name.string'   =>'اسم المنتج يحتوي علي احرف فقط ',

                'description.required'  =>'يرجي ادخال الملاحظات',
                'description.max'       =>'الملاحظات لايمكن ان تكون اكثر من 50 حرف',
                'description.string'    =>' الملاحظات تحتوي علي احرف فقط ',

                'section_id.required'   =>'يرجي اختيار قسم',
                'section_id.integer'    =>'يرجي اختيار قسم صحيح'
            ]
        );

        $affected = DB::table('products')
                        ->where('id',$id)
                        ->update($product);
        if($affected)
        {
            session()->flash('edit','تم تعديل المنتج بنجاج');
        }
        else
        {
            session()->flash('Error','لقد حدث خطأ في التعديل ');
        }
        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $deleted = DB::table('products')->where('id',$id)->delete();

        if($deleted)
            session()->flash('delete','تم حذف المنتج بنجاح');
        else
            session()->flash('Error','عفوا هناك خطأ في  حذف المنتج');

        return redirect('/products');
    }
}
