<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
//use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // $role = Role::firstOrCreate(['name' => 'dashboard-admin']);

    // // إنشاء مستخدم وتعيين الدور له
    // $user = User::firstOrCreate([
    //     'email' => 'dawdh681@gmail.com',
    // ], [
    //     'name' => 'haidar',
    //     'password' => bcrypt('hdzrahaiz123456789'), // تأكد من تشفير كلمة المرور
    // ]);

    // $user->assignRole($role); // تعيين الدور للمستخدم
    DB::table('roles')->insert([
        'role'=>'admin'
    ]);
    DB::table('roles')->insert([
        'role'=>'user'
    ]);
}
}
