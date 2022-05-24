<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = $this->faker->dateTimeInInterval('-6 months', '+6 months');
        $end = $this->faker->dateTimeBetween($start, '+3 months');

        $random = rand(1,4);
        return [
            'title' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'company_id' => $random,
            'wysiwyg' => "<p>" . implode("</p><p>", $this->faker->paragraphs(3)) . "</p>",
            'starts_at' => $start,
            'expires_at' => $end,
            'is_active' => $this->faker->boolean()
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => 1,
            ];
        });
    }

    public function statusPending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Job::STATUS_PENDING,
            ];
        });
    }

    public function statusExpired()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Job::STATUS_EXPIRED,
            ];
        });
    }

    public function statusActive()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Job::STATUS_ACTIVE,
            ];
        });
    }
}
