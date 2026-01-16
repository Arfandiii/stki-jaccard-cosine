<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Query extends Model
{
    protected $fillable = [
        'query_text','letter_type','start_date','end_date',
        'execution_time','method'
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'execution_time' => 'float',
    ];

    public function terms(): HasMany
    {
        return $this->hasMany(QueryTerm::class);
    }
    public function results(): HasMany
    {
        return $this->hasMany(QueryResult::class);
    }
}