<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueryResult extends Model
{
    protected $fillable = ['query_id', 'method', 'surat_type', 'surat_id', 'score'];
}
