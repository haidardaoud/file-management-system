<?php

 namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

class Group extends Model {

     public $table = 'groups';

      use HasFactory;

       protected $fillable = [
         'name',
          'description',
           'image'
         ];
         public function groupMember(){

             return $this->hasMany(GroupMember::class,'group_id','id');

             }
              public function file(){

                 return $this->hasMany(File::class,'group_id');
                }
                public function files(){

                  return $this->hasMany(File::class,'group_id');
                 }
                 public function logs(){

                    return $this->hasMany(Log::class);
                   }
                   
             }
