<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoices_details extends Model
{
    use HasFactory;
    protected $table = "invoices_details";
    protected $fillable =   [
        'invoice_id',
        'invoice_Date',
        'Due_date',
        'payment_date',
        'product',
        'section_id',
        'Amount_collection',
        'Amount_Commission',
        'Discount',
        'Rate_VAT',
        'Value_VAT',
        'Total',
        'Status',
        'note',
        'user'
    ];
    public $timestamps = false;
}
