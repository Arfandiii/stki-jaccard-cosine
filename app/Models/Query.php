<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;

    protected $fillable = [
        'query_text',
        'letter_type',
        'start_date',
        'end_date',
        'execution_time',
        'method',
    ];

    public function queryTerms()
    {
        return $this->hasMany(QueryTerm::class);
    }

    public function results()
    {
        return $this->hasMany(QueryResult::class);
    }

}