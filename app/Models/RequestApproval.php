<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestApproval extends Model
{
    public $table = 'request_approvals';
    use HasFactory;
    protected $fillable = [
        'file_id',
        'user_id',
        'owner_id',
        'status'
    ];
    public function file(){
        return $this->belongsTo(File::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }

}
