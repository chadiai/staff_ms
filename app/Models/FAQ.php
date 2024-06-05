<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'question',
        'answer',
        'authorization_type_id',
    ];


    public function authorization_type()
    {
        return $this->belongsTo(AuthorizationType::class)->withDefault();
    }

    public function scopeSearchName($query, $search = '%')
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
