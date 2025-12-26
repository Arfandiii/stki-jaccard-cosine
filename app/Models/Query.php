<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;

    protected $table = 'queries';

    protected $fillable = ['query_text'];

    public function queryTerms()
    {
        return $this->hasMany(QueryTerm::class);
    }
}