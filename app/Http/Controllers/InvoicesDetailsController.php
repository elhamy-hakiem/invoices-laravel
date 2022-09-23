<?php

namespace App\Http\Controllers;

use App\Models\invoices_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('invoices.invoice_details');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $invoice       = DB::table('invoices')->where('id',$request->id)->first();
        $invoiceData   = DB::table('invoices_details')->where('invoice_id',$request->id)->first();
        $invoiceAttach = DB::table('invoice_attachments')->where('invoice_id',$request->id)->first();
        $sectionName   = DB::table('sections')->where('id', $invoiceData->section_id)->value('section_name');
        $productName   = DB::table('products')->where('id', $invoiceData->product)->value('product_name');


        if(empty($invoiceData))
        {
            return back();
        }
        else
        {
            return view
                        (
                            'invoices.invoice_details',
                            [
                                'invoiceData'   => $invoiceData ,
                                'invoice'       => $invoice,
                                'sectionName'   => $sectionName,
                                'productName'   => $productName,
                                'invoiceAttach' => $invoiceAttach
                            ]
                        );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function edit(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function destroy(invoices_details $invoices_details)
    {
        //
    }

    // Start Open Invoice Attachments In Browser
    public function open_file( Request $request)
    {
        $fileId   = $request->fileId;
        $fileData = DB::table('invoice_attachments')->where('id',$fileId)->first();
        if(empty($fileData))
        {
            return redirect('/404');
        }
        else
        {
            $fileName = $fileData ->file_name;
            if(Storage::disk('public_uploads')->exists("$request->invoice_number/$fileName"))
            {
                $path = public_path("Attachments/$request->invoice_number/$fileName");

                return response()->file($path);
            }
            else
            {
                return redirect('/404');
            }
        }
    }
    // End Open Invoice Attachments In Browser

    // Start Delete Invoice Attachments In Browser
    public function delete_file(Request $request)
    {
        $id = $request->id_file;
        # DELETE OP . . .
        $op = DB::table('invoice_attachments')->where('id', $id)->delete();
        if($op)
        {
            # Remove File . . .
            unlink(public_path("Attachments/$request->invoice_number/$request->file_name"));
            session()->flash('delete','تم حذف المرفق بنجاح');
        }
        else {
            session()->flash('Error', 'حدث خطأ في حذف المرفق');
        }

        return back();
    }
    // End Delete Invoice Attachments In Browser

}
