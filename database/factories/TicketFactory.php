<?php

namespace Database\Factories;

use App\Helpers\FunctionHelper;
use App\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'code' => $this->faker->word(),
            'content' => $this->faker->paragraph(),
            'author_name' => $this->faker->name(),
            'author_email' => $this->faker->email,
            'status_id' => 5,
            'priority_id' => rand(1, 4),
            'category_id' => rand(1, 5),
            'assigned_to_user_id' => 1,
            'project_id' => 1,
            'work_start' => $this->faker->dateTimeThisYear(),
        ];
    }
}
