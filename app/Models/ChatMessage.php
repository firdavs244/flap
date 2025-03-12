<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'message', 'created_at', 'pin'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
