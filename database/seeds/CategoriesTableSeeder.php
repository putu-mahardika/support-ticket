<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker\Factory::create();
        $categories = [
            [
                'name'  =>  'Bug',
                'color' =>  '#ff0000',
            ],
            [
                'name'  =>  'Update',
                'color' =>  '#cc5200',
            ],
            [
                'name'  =>  'New Feature',
                'color' =>  '#009933',
            ],
            [
                'name'  =>  'Report',
                'color' =>  '#0000ff',
            ],
        ];

        Category::insert($categories);

        // foreach($categories as $category)
        // {
        //     Category::create([
        //         'name'  => $category,
        //         'color' => $faker->hexcolor
        //     ]);
        // }
    }
}
