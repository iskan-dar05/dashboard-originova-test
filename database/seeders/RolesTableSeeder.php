<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::pluck('id','id')->all();
        
        $role1 = Role::create(["name" => "admin"]);
        $role2 = Role::create(["name" => "user"]);
        $role3 = Role::create(["name" => "subadmin"]);

        $role1->syncPermissions($permissions);
        $role2->syncPermissions(['name' => 'edit']);
        $role3->syncPermissions(['name'=>'delete']);
   
    }
     



}
    