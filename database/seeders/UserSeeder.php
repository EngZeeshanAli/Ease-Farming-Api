<?php

namespace Database\Seeders;

use App\Models\User;
use App\Utils\Constants;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(Constants::TABLE_USERS)->insert([
            Constants::NAME => "Zeeshan Ali",
            Constants::EMAIL => "zeshan.lhr.pk@gmail.com",
            Constants::PASSWORD => Hash::make("00000000"),
            Constants::MOBILE => "03224562142",
            Constants::USER_TYPE => Constants::GUARD_FARMER
        ]);
    }
}
