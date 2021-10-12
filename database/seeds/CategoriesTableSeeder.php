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
                'name'  =>  '',
                'color' =>  '#ffffff',
                'icon'  => ''
            ],
            [
                'name'  =>  'Bug',
                'color' =>  '#ff0000',
                'icon'  =>  'fas fa-bug',
            ],
            [
                'name'  =>  'Update',
                'color' =>  '#cc5200',
                'icon'  =>  'fa fa-file',
            ],
            [
                'name'  =>  'New Feature',
                'color' =>  '#009933',
                'icon'  =>  'fas fa-money',
            ],
            [
                'name'  =>  'Report',
                'color' =>  '#0000ff',
                'icon'  =>  'fas fa-tasks',
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
