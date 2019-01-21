<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        DB::table('users')->insert([
            'name' => getenv('SITE_USER_NAME'),
            'email' => getenv('SITE_USER_EMAIL'),
            'email_verified_at' => date($mysql_format, time()),
            'password' => bcrypt(getenv('SITE_USER_PASSWORD')),
            'created_at' => date($mysql_format, time()),
            'updated_at' => date($mysql_format, time()),
        ]);
    }
}