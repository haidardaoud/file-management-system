<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    public $table = 'group_members';
    use HasFactory;
    protected $fillable = [
        'user_id',
        'group_id',
        'isOwner'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
    public function logs()
    {
        return $this->hasMany(Log::class);
    }
   

}
