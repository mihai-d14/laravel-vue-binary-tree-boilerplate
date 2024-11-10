<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        '_lft',
        '_rgt',
        'parent_id',
        'tree_type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if (!$user->password) {
                $user->password = bcrypt(Str::random(16));
            }
        });
    }
}