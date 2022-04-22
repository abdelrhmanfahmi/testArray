<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $table = "targets";
    protected $fillable = ['month' , 'target' , 'user_id'];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
