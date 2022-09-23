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

class InvoicesArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = invoices::onlyTrashed()
        ->leftJoin('invoices_details', 'invoices.id', '=', 'invoices_details.invoice_id')
        ->select('invoices.*','invoices_details.Status')
        ->get();
        return view('invoices.invoices_archived', ['invoices' => $invoices]);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->invoice_id;
        $invoiceRestore = Invoices::withTrashed()->where('id', $id)->restore();
        if($invoiceRestore)
        {
            session()->flash('restore_invoice');
            return redirect('/invoices');
        }
        else
        {
            session()->flash('restore_error');
            return redirect('/invoicesArchived');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoices            = invoices::withTrashed()->where('id',$request->invoice_id)->first();
        $invoice_number      = $invoices->invoice_number;
        $invoice_attachments = invoice_attachments::where('invoice_id',$request->invoice_id)->first();
        if(!empty($invoice_attachments))
        {
            Storage::disk('public_uploads')->deleteDirectory($invoice_number);
        }
        $query = $invoices->forceDelete();
        if($query)
        {
            session()->flash('delete_invoice');
            return redirect('/invoicesArchived');
        }
    }
}
