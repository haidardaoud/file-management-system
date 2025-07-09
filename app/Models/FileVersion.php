<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileVersion extends Model
{
    use HasFactory;
    public $table = 'file_versions';
    protected $fillable = ['file_id', 'file_path','version_number'];


public function file(){
    return $this->belongsTo(File::class);

}
}
