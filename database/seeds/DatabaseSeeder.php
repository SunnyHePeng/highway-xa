<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User,App\Models\User\Role,App\Models\User\Permission;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);

        /*$admin = new Role;
        $admin->name = 'Admin';
        $admin->display_name = '超级管理员';
        $admin->description = '管理所有权限';
        $admin->save();*/
        
        // Create User
        $user = new User;
        $user->username = 'yf';
        $user->password = bcrypt('yfen123');
        if(! $user->save()) {
            Log::info('Unable to create user '.$user->username, (array)$user->errors());
        } else {
            Log::info('Created user "'.$user->username.'" <'.$user->email.'>');
        }
        // Attach Roles to user
        $user->roles()->attach( $admin->id );
        // Create Permissions
        $manage = new Permission;
        $manage->name = 'manage_all';
        $manage->display_name = '超级管理,管理所有权限';
        $manage->save();

        $admin->perms()->sync($manage->id);

        Model::reguard();
    }
}
