<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table='roles';
    use HasFactory;
    protected $fillable=[
        'role'
    ];
    public function user(){
        return $this->hasMany(User::class,'role_id');
    }
}
