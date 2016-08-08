<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Permission;
use App\Role;

class RoleSeeder extends Seeder{
    public function run(){
        $roles = array(
            array(
                'description' => 'Site Administrator',
                'slug' => 'site-administrator',
                'default_permissions' => array(
                    array(
                        'slug' => 'manage-all-users',
                        'description' => 'Manage All Users',
                    ),
                    array(
                        'slug' => 'impersonate-all-users',
                        'description' => 'Impersonate All Users',
                    ),
                    array(
                        'slug' => 'manage-schedule',
                        'description' => 'Manage the Schedule',
                    ),
                    array(
                        'slug' => 'manage-details',
                        'description' => 'Manage the Details',
                    ),
                ),
            ),
            array(
                'description' => 'Band Administrator',
                'slug' => 'band-administrator',
                'default_permissions' => array(
                    array(
                        'slug' => 'manage-band-users',
                        'description' => 'Manage Band Users',
                    ),
                    array(
                        'slug' => 'manage-schedule',
                        'description' => 'Manage the Schedule',
                    ),
                    array(
                        'slug' => 'manage-details',
                        'description' => 'Manage the Details',
                    ),
                ),
            ),
            array(
                'description' => 'Band Member',
                'slug' => 'band-member',
                'default_permissions' => array(
                    array(
                        'slug' => 'manage-schedule',
                        'description' => 'Manage the Schedule',
                    ),
                    array(
                        'slug' => 'manage-details',
                        'description' => 'Manage the Details',
                    ),
                ),
            ),
        );
        
        foreach($roles as $role){
            $r = Role::where('slug', $role['slug'])->first();
            if (!$r){
                $r = new Role;
                $r->slug = $role['slug'];
            }
            $r->description = $role['description'];
            $r->save();
            if (isset($role['default_permissions'])){
                foreach($role['default_permissions'] as $permission){
                    $p = Permission::where('slug', $permission['slug'])->first();
                    if (!$p){
                        $p = new Permission;
                        $p->slug = $permission['slug'];
                        $p->description = $permission['description'];
                    }
                    $p->save();
                    $r->givePermissionTo($p);
                }
            }
        }
    }
}