<?php
/*
 * File name: OptionsTableSeeder.php
 * Last modified: 2021.03.01 at 21:57:23
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use DB;
use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('options')->delete();
        factory(Option::class, 100)->create();
        Option::factory()->count(100)->create();
    }
}
