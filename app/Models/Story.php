<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'photo', 'user_id', 'created_at'];

    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function groupChats()
    {
        return $this->hasMany(GroupChat::class);
    }
}
