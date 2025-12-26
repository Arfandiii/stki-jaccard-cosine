<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryTerm extends Model
{
    use HasFactory;

    protected $table = 'query_terms';

    protected $fillable = [
        'query_id',
        'term',
        'tf',
        'tfidf',
    ];

    public function queryModel()
    {
        return $this->belongsTo(Query::class);
    }
}