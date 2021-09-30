<?php

use App\Status;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker\Factory::create();
        $statuses = [
            [
                'name'  =>  'Open',
                'color' =>  '#0000ff',
            ],
            [
                'name'  =>  'Pending',
                'color' =>  '#cc5200',
            ],
            [
                'name'  =>  'Working',
                'color' =>  '#009933',
            ],
            [
                'name'  =>  'Confirm Client',
                'color' =>  '#990099',
            ],
            [
                'name'  =>  'Close',
                'color' =>  '#ff0000',
            ],
        ];
        Status::insert($statuses);

        // foreach($statuses as $status)
        // {
        //     Status::create([
        //         'name'  => $status,
        //         'color' => $faker->hexcolor
        //     ]);
        // }
    }
}
