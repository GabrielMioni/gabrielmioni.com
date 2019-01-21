<?php

use Illuminate\Database\Seeder;
use jeremykenedy\LaravelRoles\Models\Permission;
use jeremykenedy\LaravelRoles\Models\Role;
use App\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mysql_format = 'Y-m-d H:i:s';

        $admin_user = User::where('email', '=', getenv('SITE_USER_EMAIL'))->get();

        if (count($admin_user) > 0) {
            return;
        }

        $new_admin = User::create([
            'name' => getenv('SITE_USER_NAME'),
            'email' => getenv('SITE_USER_EMAIL'),
            'email_verified_at' => date($mysql_format, time()),
            'password' => bcrypt(getenv('SITE_USER_PASSWORD')),
            'created_at' => sqlFormatDate(),
            'updated_at' => sqlFormatDate(),
        ]);

        $this->attachRole($new_admin);

    }

    protected function attachRole(User $new_admin) {
        $adminRole = Role::where('name', '=', 'Admin')->first();
        $permissions = Permission::all();

        $new_admin->attachRole($adminRole);
        foreach ($permissions as $permission) {
            $new_admin->attachPermission($permission);
        }
    }
}