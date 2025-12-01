<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestPostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $posts = [
            [
                'title' => 'Sed aut veniam cill',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'user_id' => $users->first()->id,
                'country_id' => 1,
                'state_id' => 1,
                'city_id' => 1,
                'section_id' => 1,
                'category_id' => 1,
                'status' => 'approved',
                'payment_status' => 'free',
                'age' => 25,
                'email' => 'test@example.com',
                'phone' => '+1234567890',
            ],
            [
                'title' => 'Consectetur adipiscing',
                'description' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'user_id' => $users->first()->id,
                'country_id' => 1,
                'state_id' => 1,
                'city_id' => 1,
                'section_id' => 1,
                'category_id' => 1,
                'status' => 'pending',
                'payment_status' => 'paid',
                'age' => 30,
                'email' => 'another@example.com',
                'phone' => '+1234567891',
            ],
            [
                'title' => 'Ut enim ad minim',
                'description' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
                'user_id' => $users->first()->id,
                'country_id' => 1,
                'state_id' => 1,
                'city_id' => 1,
                'section_id' => 1,
                'category_id' => 1,
                'status' => 'approved',
                'payment_status' => 'free',
                'age' => 28,
                'email' => 'third@example.com',
                'phone' => '+1234567892',
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }
    }
}
