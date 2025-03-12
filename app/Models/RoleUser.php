<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $fillable = ['user_id', 'role_id'];

//    public function role()
//    {
//        return $this->hasOne(Role::class);
//    }
//
//    public function user()
//    {
//        return $this->hasOne(User::class);
//    }
}

