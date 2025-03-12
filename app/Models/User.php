<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $perPage = 15;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            return $this->hasRole($roles);
        }

        return false;
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function messages()
    {
        return $this->hasManyThrough(ChatMessage::class, ChatMessage::class, 'user_id', 'id');
    }

    public function stories(){
        return $this->hasMany(Story::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function teachersChats()
    {
        return $this->hasMany(TeachersChat::class);
    }

//    public function sessions()
//    {
//        return $this->hasMany(Session::class);
//    }
}
