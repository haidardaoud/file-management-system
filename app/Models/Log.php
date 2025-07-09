<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_id',
        'group_member_id',
        'action',
        'details',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الملف
    public function file()
    {
        return $this->belongsTo(File::class);
    }
    public function groupMember()
    {
        return $this->belongsTo(GroupMember::class, 'user_id', 'user_id');  // باستخدام user_id في العلاقة
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

}
