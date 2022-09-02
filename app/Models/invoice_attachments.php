<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice_attachments extends Model
{
    use HasFactory;
    protected $table = "invoice_attachments";
    protected $fillable =   [
        'file_name',
        'Created_by',
        'invoice_id',
    ];
}
