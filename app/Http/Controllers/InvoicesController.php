<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_details;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Mail\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $invoices = DB::table('invoices')
            ->leftJoin('invoices_details', 'invoices.id', '=', 'invoices_details.invoice_id')
            ->select('invoices.*','invoices_details.Status')
            ->where('invoices.deleted_at',null)
            ->get();
        return view('invoices.invoices', ['invoices' => $invoices]);
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
                'Section'           => 'required|integer',
                'Amount_collection' => 'required|numeric',
                'Amount_Commission' => 'required|numeric',
                'Discount'          => 'required|string',
                'Value_VAT'         => 'required|numeric',
                'Rate_VAT'          => 'required|string',
                'Total'             => 'required|numeric',
                'note'              => 'max:50',
            ],
            [
                'invoice_number.required'   =>'???????? ?????????? ?????? ????????????????',
                'invoice_number.unique'     =>'?????? ???????????????? ???????? ??????????',
                'invoice_number.Integer'    =>'?????? ???????????????? ?????????? ?????? ?????????? ?????? ',

                'invoice_Date.required'     =>'???????? ?????????? ?????????? ????????????????',
                'invoice_Date.date'         =>'?????????? ???????????????? ?????? ???????? ',

                'Due_date.required'         =>'???????? ?????????? ?????????? ??????????????????',
                'Due_date.date'             =>'?????????? ?????????????????? ?????? ???????? ',

                'product.required'          =>'???????? ???????????? ????????????',
                'product.Integer'           =>' ???????????? ?????? ???????? ',

                'Section.required'       =>'????????  ???????????? ??????????',
                'Section.Integer'        =>'?????????? ?????? ???????? ',

                'Amount_collection.required'=>'???????? ?????????? ???????? ??????????????',
                'Amount_collection.numeric' =>'???????? ?????????????? ?????? ???????? ',

                'Amount_Commission.required'=>'???????? ??????????  ???????? ??????????????',
                'Amount_Commission.numeric' =>'???????? ??????????????  ?????? ???????? ',

                'Discount.required'         =>'???????? ?????????? ???????? ??????????',
                'Discount.string'          =>'???????? ?????????? ?????? ???????? ',

                'Value_VAT.required'        =>'???????? ?????????? ???????? ?????????? ???????????? ??????????????',
                'Value_VAT.numeric'           =>'???????? ?????????? ???????????? ??????????????  ?????? ??????????',

                'Rate_VAT.required'         =>'???????? ?????????? ???????? ?????????? ???????????? ??????????????',
                'Rate_VAT.string'            =>'???????? ?????????? ???????????? ??????????????  ?????? ??????????',

                'Total.required'            =>'???????? ?????????? ???????????????? ???????? ?????????????? ',
                'Total.numeric'               =>'???????? ?????????? ???????????? ??????????????  ?????? ??????????',

                'note.max'                =>'?????????????????? ???? ?????????? 50 ??????',
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
                session()->flash('Error', '?????? ???????????? ?????? ?????????? ???? ?????? ??????????');
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
            'Status' => 0,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if($request->hasFile('pic'))
        {
            $imageValidate = $request->validate
            (
                [
                    "pic"           => 'file|max:2048|mimes:pdf,jpeg,jpg,png'
                ],
                [
                    "pic.mimes"     => '???? ?????? ???????????????? : ?????? ?????? ???? ?????? ???????????? ?????? ???????????? ?????? ?????????????? ??????????????',
                    "pic.file"      => '???? ?????? ???????????????? : ?????? ?????? ???? ?????? ???????????? ?????? ???????????? ?????? ?????????????? ??????????????',
                    "pic.max"       => '???? ?????? ???????????????? : ?????? ?????? ???? ?????? ???????????? ?????? ?????? ???????? ?????? ?????????? ???? 2 ????????',

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

        session()->flash('Add', '???? ?????????? ???????????????? ??????????');
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
    public function edit(Request $request)
    {
        $invoice_id = $request->id;
        $invoice               = DB::table('invoices')->where('id',$invoice_id)->first();
        $invoiceDetails        = DB::table('invoices_details')->where('invoice_id',$invoice_id)->first();
        $invoiceAttachments    = DB::table('invoice_attachments')->where('invoice_id',$invoice_id)->first();
        $sections              = DB::table('sections')->get();
        return view
                    (
                        'invoices.invoiceEdit',
                        [
                            'invoice'               => $invoice ,
                            'invoiceAttachments'    => $invoiceAttachments ,
                            'invoiceDetails'        => $invoiceDetails ,
                            'sections'              => $sections ,
                        ]
                    );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $invoice_id  = $request->invoiceId;

        $invoice  = $request->validate
        (
            [
                'invoice_number'    => 'required|Integer|unique:invoices,invoice_number,'.$invoice_id
            ],
            [
                'invoice_number.required'   =>'???????? ?????????? ?????? ????????????????',
                'invoice_number.unique'     =>'?????? ???????????????? ???????? ??????????',
                'invoice_number.Integer'    =>'?????? ???????????????? ?????????? ?????? ?????????? ?????? '
            ]
        );

        $invoiceDetails = $request->validate
        (
            [
                'invoice_Date'      => 'required|date',
                'Due_date'          => 'required|date',
                'product'           => 'required|integer',
                'section_id'        => 'required|integer',
                'Amount_collection' => 'required|numeric',
                'Amount_Commission' => 'required|numeric',
                'Discount'          => 'required|string',
                'Value_VAT'         => 'required|numeric',
                'Rate_VAT'          => 'required|string',
                'Total'             => 'required|numeric',
                'note'              => 'max:50',
            ],
            [
                'invoice_Date.required'     =>'???????? ?????????? ?????????? ????????????????',
                'invoice_Date.date'         =>'?????????? ???????????????? ?????? ???????? ',

                'Due_date.required'         =>'???????? ?????????? ?????????? ??????????????????',
                'Due_date.date'             =>'?????????? ?????????????????? ?????? ???????? ',

                'product.required'          =>'???????? ???????????? ????????????',
                'product.Integer'           =>' ???????????? ?????? ???????? ',

                'section_id.required'       =>'????????  ???????????? ??????????',
                'section_id.Integer'        =>'?????????? ?????? ???????? ',

                'Amount_collection.required'=>'???????? ?????????? ???????? ??????????????',
                'Amount_collection.numeric' =>'???????? ?????????????? ?????? ???????? ',

                'Amount_Commission.required'=>'???????? ??????????  ???????? ??????????????',
                'Amount_Commission.numeric' =>'???????? ??????????????  ?????? ???????? ',

                'Discount.required'         =>'???????? ?????????? ???????? ??????????',
                'Discount.string'           =>'???????? ?????????? ?????? ???????? ',

                'Value_VAT.required'        =>'???????? ?????????? ???????? ?????????? ???????????? ??????????????',
                'Value_VAT.numeric'         =>'???????? ?????????? ???????????? ??????????????  ?????? ??????????',

                'Rate_VAT.required'         =>'???????? ?????????? ???????? ?????????? ???????????? ??????????????',
                'Rate_VAT.string'           =>'???????? ?????????? ???????????? ??????????????  ?????? ??????????',

                'Total.required'            =>'???????? ?????????? ???????????????? ???????? ?????????????? ',
                'Total.numeric'             =>'???????? ?????????? ???????????? ??????????????  ?????? ??????????',

                'note.max'                  =>'?????????????????? ???? ?????????? 50 ??????',
            ]
        );

        //Start Check if the product in this section
        if(!empty($request->product))
        {
            $product_id = intval($request->product);
            $section_id = DB::table('products')
                        ->where('id',$product_id)
                        ->value("section_id");

            $sectionInput = intval($request->section_id);

            if($sectionInput != $section_id)
            {
                session()->flash('Error', '?????? ???????????? ?????? ?????????? ???? ?????? ??????????');
            }
        }
        //End Check if the product in this section

        $affected_1 = DB::table('invoices')
                        ->where('id',$invoice_id)
                        ->update($invoice);

        $affected_2 = DB::table('invoices_details')
                        ->where('invoice_id',$invoice_id)
                        ->update($invoiceDetails);
        $affected_3 = false;

        $affected_4 = false;

        if($request->hasFile('pic'))
        {
            // Attachment Validation
            $attachValidate = $request->validate
            (
                [
                    "pic"           => 'file|max:2048|mimes:pdf,jpeg,jpg,png'
                ],
                [
                    "pic.mimes"     => '???? ?????? ???????????????? : ?????? ?????? ???? ?????? ???????????? ?????? ???????????? ?????? ?????????????? ??????????????',
                    "pic.file"      => '???? ?????? ???????????????? : ?????? ?????? ???? ?????? ???????????? ?????? ???????????? ?????? ?????????????? ??????????????',
                    "pic.max"       => '???? ?????? ???????????????? : ?????? ?????? ???? ?????? ???????????? ?????? ?????? ???????? ?????? ?????????? ???? 2 ????????',

                ]
            );
            // get new attachment Name
            $image = $request->file('pic');
            $newImageName = md5(rand(0,100000)).'.'.$image->getClientOriginalExtension();

            // Check If Have Old Attachments
            $oldAttach     = DB::table('invoice_attachments')->where('invoice_id', $invoice_id)->first();
            if(!empty($oldAttach))
            {
                $oldFileName   = $oldAttach-> file_name;
                unlink(public_path("Attachments/$request->invoice_number/$oldFileName"));
                $affected_3   = DB::table('invoice_attachments')
                ->where('invoice_id',$invoice_id)
                ->update(
                            ['file_name'  => $newImageName],
                            ['Created_by' => (Auth::user()->name)]
                        );
            }
            else
            {
                $affected_4 = invoice_attachments::create([
                    'file_name' => $newImageName,
                    'invoice_id' => $invoice_id,
                    'Created_by' => (Auth::user()->name),
                ]);
            }

            $invoice_number =$request->invoice_number;
            $request->pic->move(public_path('Attachments/' . $invoice_number), $newImageName);
        }
        if($affected_1 || $affected_2 || $affected_3 || $affected_4)
        {
            session()->flash('update', '???? ?????????? ???????????????? ??????????');
        }
        else
        {
            session()->flash('Error','?????? ?????? ?????? ???? ??????????????');
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice_id     = $request->invoice_id;
        $invoice_data   = invoices::where('id',$invoice_id)->first();
        $invoice_number = $invoice_data->invoice_number;
        $deleteOption = $request->deleteOption;
        if(!empty($invoice_data))
        {
            if($deleteOption == 1)
            {
                $query = $invoice_data-> delete();
                if($query)
                {
                    session()->flash('archive_invoice');
                    return redirect('/invoicesArchived');
                }
            }
            elseif($deleteOption == 2)
            {
                $invoice_attachments = invoice_attachments::where('invoice_id',$invoice_id)->first();
                if(!empty($invoice_attachments))
                {
                    Storage::disk('public_uploads')->deleteDirectory($invoice_number);
                }
                $query = $invoice_data->forceDelete();
                if($query)
                {
                    session()->flash('delete_invoice');
                    return redirect('/invoices');
                }
            }

        }
    }
    // Function to Change Payment Paid or unpaid invoices
    public function paymentStatus(Request $request)
    {
        $invoice_id     = $request->invoice_id;
        $invoice_data   = invoices_details::where('invoice_id',$invoice_id)->first();
        $paymentStatus  = $request->paymentStatus;
        if(!empty($invoice_data))
        {
            if($invoice_data->Status == $paymentStatus)
            {
                return back();
            }
            if($paymentStatus == 0 || $paymentStatus == 1)
            {
                if($paymentStatus == 0)
                {
                    $query = DB::table('invoices_details')
                    ->where('invoice_id',$invoice_id)
                    ->update([
                                'Status'       =>$paymentStatus,
                                'payment_date' => null
                            ]);
                }
                else
                {
                    $query = DB::table('invoices_details')
                    ->where('invoice_id',$invoice_id)
                    ->update([
                                'Status'       => $paymentStatus,
                                'payment_date' => date('y-m-d'),
                            ]);
                }


                if($query)
                {
                    session()->flash('payment_invoice');
                    return redirect('/invoices');
                }
            }
            else
            {
                session()->flash('error_invoice');
                return redirect('/invoices');
            }
        }
        else
        {
            session()->flash('error_invoice');
            return redirect('/invoices');
        }
    }

    // Function to return paid or unpaid invoices
    public function status(Request $request)
    {
        $paymentStatus = $request ->status;
        if($paymentStatus == 0)
        {
            $invoices = DB::table('invoices')
            ->leftJoin('invoices_details', 'invoices.id', '=', 'invoices_details.invoice_id')
            ->select('invoices.*','invoices_details.Status','invoices_details.payment_date')
            ->where('invoices_details.Status',0)
            ->get();
            return view('invoices.invoicesPaymentStatus', ['invoices' => $invoices]);
        }
        elseif($paymentStatus == 1)
        {
            $invoices = DB::table('invoices')
            ->leftJoin('invoices_details', 'invoices.id', '=', 'invoices_details.invoice_id')
            ->select('invoices.*','invoices_details.Status','invoices_details.payment_date')
            ->where('invoices_details.Status',1)
            ->get();
            return view('invoices.invoicesPaymentStatus', ['invoices' => $invoices]);
        }
        else
        {
            return back();
        }
    }
    public function getproducts($id)
    {
        $products = DB::table('products')->where('section_id',$id)->pluck("product_name","id");
        return json_encode($products);
    }
}
