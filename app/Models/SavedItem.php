<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Resource;
use App\Models\User;

class SavedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'saveable_id',
        'saveable_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saveable()
    {
        return $this->morphTo();
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
