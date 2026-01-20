<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Query extends Model
{
    use HasFactory;
    protected $table = 'queries';
    protected $fillable = [
        'user_id',
        'query_text', 
        'letter_type', 
        'start_date', 
        'end_date',
        'execution_time',
        'cosine_time',
        'jaccard_time',
        'preprocessing_time',
        'results_count',
        'avg_cosine_score',
        'avg_jaccard_score'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'execution_time' => 'float',
        'cosine_time' => 'float',
        'jaccard_time' => 'float',
        'preprocessing_time' => 'float',
        'avg_cosine_score' => 'float',
        'avg_jaccard_score' => 'float'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function terms(): HasMany
    {
        return $this->hasMany(QueryTerm::class);
    }
}