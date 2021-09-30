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
                'name'   =>  'Low',
                'color'  =>  '#009933'
            ],
            [
                'name'   =>  'Medium',
                'color'  =>  '#cc5200'
            ],
            [
                'name'   =>  'High',
                'color'  =>  '#ff0000'
            ],
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
