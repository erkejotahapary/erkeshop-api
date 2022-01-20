<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [];
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 5; $i++) {
            $avatar_path = 'C:\xampp\htdocs\larashop-api\public\images\users';
            $avatar_fullpath = $faker->image(
                $avatar_path,
                420,
                320,
                'people',
                true,
                true,
                null
            );
            $avatar =  explode('\\', $avatar_fullpath)[7];

            $users[$i] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('123456'),
                'roles' => json_encode(['CUSTOMER']),
                'avatar' => $avatar,
                'status' => 'ACTIVE',
                'created_at' => Carbon\Carbon::now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
