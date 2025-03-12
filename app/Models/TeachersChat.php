<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachersChat extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'file', 'created_at', 'pin'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
