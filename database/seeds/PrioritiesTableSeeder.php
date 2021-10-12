<?php

use App\Priority;
use Illuminate\Database\Seeder;

class PrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker\Factory::create();
        $priorities = [
            [
                'name'  =>  'No Priority',
                'color' =>  '#000000',
                'icon'  =>  '',
            ],
            [
                'name'   =>  'Low',
                'color'  =>  '#009933',
                'icon'  =>   'fas fa-battery-quarter',
            ],
            [
                'name'   =>  'Medium',
                'color'  =>  '#cc5200',
                'icon'  =>    'fas fa-battery-half',
            ],
            [
                'name'   =>  'High',
                'color'  =>  '#ff0000',
                'icon'  =>    'fas fa-battery-full',
            ]
        ];

        Priority::insert($priorities);

        // foreach($priorities as $priority)
        // {
        //     Priority::create([
        //         'name'  => $priority,
        //         'color' => $faker->hexcolor
        //     ]);
        // }
    }
}
