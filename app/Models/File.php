<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $table ='files';
    use HasFactory;
    protected $fillable = [
        'user_id',
        'group_id',
        'name',
        'filePath',
        'isAvailable'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
    public function requestApproval(){
    return $this->hasMany(RequestApproval::class, 'file_id');
    }
    public function uploadedBy(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function versions(){
        return $this->hasMany(FileVersion::class, 'file_id');
        }
        public function logs()
        {
            return $this->hasMany(Log::class);
        }


}
