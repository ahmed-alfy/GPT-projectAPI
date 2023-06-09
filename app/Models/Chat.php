<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_msg',
        'reply',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    protected $hidden = [
        'updated_at'
    ];
}
