<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $table = 'notifications';
    protected $fillable = ['type','notifiable_id','notifiable_type','data','read_at'];
// Cast the id attribute to string
protected $casts = [
    'id' => 'string',
];
    // Define the relationship with User
    public function notifiable()
    {
        return $this->morphTo();
    }

    // Define a scope for unread notifications
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
