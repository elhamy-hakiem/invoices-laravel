<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_details;
use App\Models\invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('invoices.invoices');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = DB::table('sections')->get();
        return view('invoices.add_invoice',['sections' => $sections]);
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
                'invoice_number'    => 'required|unique:invoices|Integer',
                'invoice_Date'      => 'required|date',
                'Due_date'          => 'required|date',
                'product'           => 'required|integer',
                'Section'        => 'required|integer',
                'Amount_collection' => 'required|integer',
                'Amount_Commission' => 'required|integer',
                'Discount'          => 'required|integer',
                'Value_VAT'         => 'required|numeric',
                'Rate_VAT'          => 'required|string',
                'Total'             => 'required|numeric',
                'note'              => 'max:50',
            ],
            [
                'invoice_number.required'   =>'يرجي ادخال رقم الفاتورة',
                'invoice_number.unique'     =>'رقم الفاتورة مسجل مسبقا',
                'invoice_number.Integer'    =>'رقم الفاتورة يحتوي علي ارقام فقط ',

                'invoice_Date.required'     =>'يرجي ادخال تاريخ الفاتورة',
                'invoice_Date.date'         =>'تاريخ الفاتورة غير صحيح ',

                'Due_date.required'         =>'يرجي ادخال تاريخ الاستحقاق',
                'Due_date.date'             =>'تاريخ الاستحقاق غير صحيح ',

                'product.required'          =>'يرجي اختيار المنتج',
                'product.Integer'           =>' المنتج غير صحيح ',

                'Section.required'       =>'يرجي  اختيار القسم',
                'Section.Integer'        =>'القسم غير صحيح ',

                'Amount_collection.required'=>'يرجي ادخال مبلغ التحصيل',
                'Amount_collection.Integer' =>'مبلغ التحصيل غير صحيح ',

                'Amount_Commission.required'=>'يرجي ادخال  مبلغ العمولة',
                'Amount_Commission.Integer' =>'مبلغ العمولة  غير صحيح ',

                'Discount.required'         =>'يرجي ادخال مبلغ الخصم',
                'Discount.Integer'          =>'مبلغ الخصم غير صحيح ',

                'Value_VAT.required'        =>'يرجي ادخال قيمة ضريبة القيمة المضافة',
                'Value_VAT.numeric'           =>'قيمة ضريبة القيمة المضافة  غير صحيحه',

                'Rate_VAT.required'         =>'يرجي ادخال نسبة ضريبة القيمة المضافة',
                'Rate_VAT.string'            =>'نسبة ضريبة القيمة المضافة  غير صحيحه',

                'Total.required'            =>'يرجي ادخال الاجمالي شامل الضريبة ',
                'Total.numeric'               =>'نسبة ضريبة القيمة المضافة  غير صحيحه',

                'note.max'                =>'الملاحظات لا تتخطي 50 حرف',
            ]
        );

        //Start Check if the product in this section
        if(!empty($request->product))
        {
            $product_id = intval($request->product);
            $section_id = DB::table('products')
                        ->where('id',$product_id)
                        ->value("section_id");

            $sectionInput = intval($request->Section);

            if($sectionInput != $section_id)
            {
                session()->flash('Error', 'هذا المنتج غير موجود في هذا القسم');
            }
        }
        //End Check if the product in this section

        invoices::create([
            "invoice_number"   => $request->invoice_number
        ]);

        $invoice_id = invoices::latest()->first()->id;

        invoices_details::create([
            'invoice_id' => $invoice_id,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if($request->hasFile('pic'))
        {
            $imageValidate = $request->validate
            (
                [
                    "pic"           => 'file|size:2048|mimes:pdf,jpeg,jpg,png'
                ],
                [
                    "pic.mimes"     => 'يجب اختيار ملف بالصيغة الموضحه',
                    "pic.file"      => 'يجب اختيار ملف بالصيغة الموضحه',
                    "pic.size"      => 'يجب الا يزيد حجم الملف عن 2 ميجا',

                ]
            );
            $invoice_id = invoices::latest()->first()->id;
            $image = $request->file('pic');
            $newImageName = md5(rand(0,100000)).'.'.$image->getClientOriginalExtension();
            invoice_attachments::create([
                'file_name' => $newImageName,
                'invoice_id' => $invoice_id,
                'Created_by' => (Auth::user()->name),
            ]);
            $invoice_number =$request->invoice_number;
            $request->pic->move(public_path('Attachments/' . $invoice_number), $newImageName);
        }
        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show(invoices $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit(invoices $invoices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices $invoices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(invoices $invoices)
    {
        //
    }

    public function getproducts($id)
    {
        $products = DB::table('products')->where('section_id',$id)->pluck("product_name","id");
        return json_encode($products);
    }
}
