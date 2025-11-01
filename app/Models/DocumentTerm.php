<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTerm extends Model
{
    protected $fillable = ['doc_id','doc_type','term','tf'];
    public $timestamps = false;
}
